<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Stored Procedure for Processing Orders
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_ProcessOrder;
            CREATE PROCEDURE sp_ProcessOrder(
                IN parameter_user_id INT,
                IN parameter_shipping_address TEXT,
                IN parameter_payment_method VARCHAR(50),
                IN parameter_customer_name VARCHAR(255),
                IN parameter_customer_email VARCHAR(255),
                IN parameter_customer_phone VARCHAR(20),
                IN parameter_coupon_code VARCHAR(50),
                IN parameter_discount_amount DECIMAL(12,2),
                IN parameter_shipping_fee DECIMAL(10,2),
                IN parameter_notes TEXT,
                IN parameter_extra_packaging BOOLEAN,
                OUT parameter_order_id INT
            )
            BEGIN
                DECLARE variable_cart_id INT;
                DECLARE variable_subtotal DECIMAL(12,2);
                DECLARE variable_total DECIMAL(12,2);
                DECLARE variable_order_number VARCHAR(50);
                DECLARE variable_day_seq INT;
                
                -- Error handling
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                -- Get and Lock Cart
                SELECT id INTO variable_cart_id FROM carts WHERE user_id = parameter_user_id FOR UPDATE;
                
                IF variable_cart_id IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cart not found';
                END IF;

                -- Check Stock
                IF EXISTS (
                    SELECT 1 FROM cart_items 
                    JOIN products ON cart_items.product_id = products.id 
                    WHERE cart_items.cart_id = variable_cart_id AND products.stock_quantity < cart_items.quantity
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock for one or more items';
                END IF;

                -- Calculate Subtotal
                SELECT IFNULL(SUM(products.price * cart_items.quantity), 0) INTO variable_subtotal
                FROM cart_items
                JOIN products ON cart_items.product_id = products.id
                WHERE cart_items.cart_id = variable_cart_id;

                SET variable_total = variable_subtotal - parameter_discount_amount + parameter_shipping_fee;

                -- Calculate Daily Sequence (Start with 1)
                SELECT COUNT(*) + 1 INTO variable_day_seq 
                FROM orders 
                WHERE DATE(created_at) = CURDATE();

                -- Generate Order Number: BSMF-YY/MM/DD-ID-000
                SET variable_order_number = CONCAT(
                    'BSMF-', 
                    DATE_FORMAT(NOW(), '%y/%m/%d'), 
                    '-', 
                    parameter_user_id, 
                    '-', 
                    LPAD(variable_day_seq, 3, '0')
                );

                -- Create Order
                INSERT INTO orders (
                    order_number, user_id, status, subtotal, discount_amount, 
                    shipping_fee, total_amount, coupon_code, customer_name, 
                    customer_email, customer_phone, shipping_address, payment_method, 
                    extra_packaging_requested, notes, placed_at, created_at, updated_at
                ) VALUES (
                    variable_order_number, parameter_user_id, 'pending', variable_subtotal, parameter_discount_amount,
                    parameter_shipping_fee, variable_total, parameter_coupon_code, parameter_customer_name,
                    parameter_customer_email, parameter_customer_phone, parameter_shipping_address, parameter_payment_method,
                    parameter_extra_packaging, parameter_notes, NOW(), NOW(), NOW()
                );

                SET parameter_order_id = LAST_INSERT_ID();

                -- Move items to order_items
                INSERT INTO order_items (
                    order_id, product_id, product_name, product_brand, 
                    product_image, quantity, price, total, created_at, updated_at
                )
                SELECT 
                    parameter_order_id, cart_items.product_id, products.name, brands.name, 
                    products.main_image, cart_items.quantity, products.price, (products.price * cart_items.quantity), NOW(), NOW()
                FROM cart_items
                JOIN products ON cart_items.product_id = products.id
                LEFT JOIN brands ON products.brand_id = brands.id
                WHERE cart_items.cart_id = variable_cart_id;

                -- Update Stock
                UPDATE products
                JOIN cart_items ON products.id = cart_items.product_id
                SET products.stock_quantity = products.stock_quantity - cart_items.quantity
                WHERE cart_items.cart_id = variable_cart_id;

                -- Clean up cart
                DELETE FROM cart_items WHERE cart_id = variable_cart_id;
                
                -- Update Coupon usage
                IF parameter_coupon_code IS NOT NULL AND parameter_coupon_code != '' THEN
                    -- Validate coupon
                    SELECT id INTO @coupon_id FROM coupons 
                    WHERE coupon_code = parameter_coupon_code;
                    
                    IF @coupon_id IS NULL THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid coupon code';
                    END IF;
                    
                    -- Check if coupon is active and not expired
                    SELECT is_active, expires_at, usage_limit, times_used INTO @is_active, @expires_at, @usage_limit, @times_used 
                    FROM coupons WHERE id = @coupon_id;
                    
                    IF @is_active = 0 OR (@expires_at IS NOT NULL AND @expires_at < NOW()) OR (@usage_limit IS NOT NULL AND @times_used >= @usage_limit) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon is no longer valid';
                    END IF;
                    
                    -- Check if user has already used this coupon
                    IF EXISTS (
                        SELECT 1 FROM orders 
                        WHERE user_id = parameter_user_id 
                        AND coupon_code = parameter_coupon_code 
                        AND status != 'cancelled'
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You have already used this coupon';
                    END IF;
                    
                    -- Update usage count
                    UPDATE coupons SET times_used = times_used + 1 WHERE id = @coupon_id;
                END IF;

                COMMIT;
            END;
        ");

        // No triggers or views needed at this stage.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessOrder;");
    }
};
