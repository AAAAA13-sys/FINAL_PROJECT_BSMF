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
            DROP PROCEDURE IF EXISTS Procedure_ProcessOrder;
            CREATE PROCEDURE Procedure_ProcessOrder(
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

                -- Generate Order Number
                SET variable_order_number = CONCAT('BSG-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(parameter_user_id, 4, '0'), '-', LPAD(FLOOR(RAND() * 1000), 3, '0'));

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
                IF parameter_coupon_code IS NOT NULL THEN
                    UPDATE coupons SET times_used = times_used + 1 WHERE code = parameter_coupon_code;
                END IF;

                COMMIT;
            END;
        ");

        // 2. View for Best Selling Products
        DB::unprepared("
            CREATE OR REPLACE VIEW View_BestSellingProducts AS
            SELECT 
                products.id, products.name, brands.name as brand_name, 
                SUM(order_items.quantity) as total_sold,
                SUM(order_items.total) as total_revenue,
                products.stock_quantity as current_stock
            FROM products
            JOIN order_items ON products.id = order_items.product_id
            JOIN orders ON order_items.order_id = orders.id
            LEFT JOIN brands ON products.brand_id = brands.id
            WHERE orders.status != 'cancelled'
            GROUP BY products.id, products.name, brands.name, products.stock_quantity
            ORDER BY total_sold DESC;
        ");

        // 3. View for Customer Spending
        DB::unprepared("
            CREATE OR REPLACE VIEW View_CustomerSpending AS
            SELECT 
                users.id, users.name, users.email,
                COUNT(orders.id) as total_orders,
                SUM(orders.total_amount) as lifetime_value
            FROM users
            LEFT JOIN orders ON users.id = orders.user_id
            GROUP BY users.id, users.name, users.email
            ORDER BY lifetime_value DESC;
        ");


        // 4. Trigger for Welcome Coupon
        DB::unprepared("
            DROP TRIGGER IF EXISTS Trigger_AfterUserRegistration;
            CREATE TRIGGER Trigger_AfterUserRegistration
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO coupons (
                    code, 
                    name, 
                    discount_type, 
                    discount_value, 
                    min_order_amount, 
                    usage_limit, 
                    is_active, 
                    created_at, 
                    updated_at
                ) VALUES (
                    CONCAT('WELCOME_', NEW.username), 
                    CONCAT('Welcome Gift for ', NEW.name), 
                    'percentage', 
                    10.00, 
                    0.00, 
                    1, 
                    1, 
                    NOW(), 
                    NOW()
                );
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS Procedure_ProcessOrder;");
        DB::unprepared("DROP VIEW IF EXISTS View_BestSellingProducts;");
        DB::unprepared("DROP VIEW IF EXISTS View_CustomerSpending;");
        DB::unprepared("DROP TRIGGER IF EXISTS Trigger_AfterUserRegistration;");
    }
};
