<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            -- 1. sp_ProcessOrder
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
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                SELECT id INTO variable_cart_id FROM carts WHERE user_id = parameter_user_id FOR UPDATE;
                
                IF variable_cart_id IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cart not found';
                END IF;

                SELECT IFNULL(SUM(products.price * cart_items.quantity), 0) INTO variable_subtotal
                FROM cart_items
                JOIN products ON cart_items.product_id = products.id
                WHERE cart_items.cart_id = variable_cart_id;

                SET variable_total = variable_subtotal - parameter_discount_amount + parameter_shipping_fee;

                SET variable_order_number = CONCAT('BSG-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(parameter_user_id, 4, '0'), '-', LPAD(FLOOR(RAND() * 1000), 3, '0'));

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

                INSERT INTO order_items (
                    order_id, product_id, product_name, product_brand, 
                    product_image, quantity, price, total, created_at, updated_at
                )
                SELECT 
                    parameter_order_id, cart_items.product_id, products.name, brands.name, 
                    products.main_image, cart_items.quantity, products.price, (products.price * cart_items.quantity), NOW(), NOW()
                FROM cart_items
                JOIN products ON cart_items.product_id = products.id
                JOIN brands ON products.brand_id = brands.id
                WHERE cart_items.cart_id = variable_cart_id;

                UPDATE products
                JOIN cart_items ON products.id = cart_items.product_id
                SET products.stock_quantity = products.stock_quantity - cart_items.quantity
                WHERE cart_items.cart_id = variable_cart_id;

                DELETE FROM cart_items WHERE cart_id = variable_cart_id;
                
                IF parameter_coupon_code IS NOT NULL AND parameter_coupon_code != '' THEN
                    UPDATE coupons SET times_used = times_used + 1 WHERE coupon_code = parameter_coupon_code;
                END IF;

                COMMIT;
            END;

            -- 2. sp_CancelOrder
            DROP PROCEDURE IF EXISTS sp_CancelOrder;
            CREATE PROCEDURE sp_CancelOrder(
                IN parameter_order_id INT,
                IN parameter_user_id INT,
                IN parameter_reason TEXT
            )
            BEGIN
                DECLARE variable_status VARCHAR(20);
                DECLARE variable_coupon_code VARCHAR(50);
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                SELECT status, coupon_code INTO variable_status, variable_coupon_code 
                FROM orders WHERE id = parameter_order_id FOR UPDATE;

                IF variable_status IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Order not found';
                END IF;

                IF variable_status != 'pending' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Only pending orders can be cancelled';
                END IF;

                UPDATE orders 
                SET status = 'cancelled', 
                    cancelled_at = NOW(),
                    cancellation_reason = parameter_reason
                WHERE id = parameter_order_id;

                UPDATE products
                JOIN order_items ON products.id = order_items.product_id
                SET products.stock_quantity = products.stock_quantity + order_items.quantity
                WHERE order_items.order_id = parameter_order_id;

                IF variable_coupon_code IS NOT NULL AND variable_coupon_code != '' THEN
                    UPDATE coupons SET times_used = times_used - 1 WHERE coupon_code = variable_coupon_code;
                END IF;

                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, created_at, updated_at)
                VALUES (parameter_user_id, 'ORDER_CANCELLED', CONCAT('Cancelled order #', parameter_order_id), 'App\\\\Models\\\\Order', parameter_order_id, NOW(), NOW());

                COMMIT;
            END;

            -- 3. sp_GetAdminDashboardStats
            DROP PROCEDURE IF EXISTS sp_GetAdminDashboardStats;
            CREATE PROCEDURE sp_GetAdminDashboardStats(
                OUT total_revenue DECIMAL(12,2),
                OUT total_orders INT,
                OUT total_products INT,
                OUT total_customers INT,
                OUT low_stock_count INT,
                OUT pending_orders_count INT
            )
            BEGIN
                SELECT IFNULL(SUM(total_amount), 0) INTO total_revenue FROM orders WHERE status != 'cancelled';
                SELECT COUNT(*) INTO total_orders FROM orders;
                SELECT COUNT(*) INTO total_products FROM products;
                SELECT COUNT(*) INTO total_customers FROM users WHERE role = 'customer';
                SELECT COUNT(*) INTO low_stock_count FROM products WHERE stock_quantity <= low_stock_threshold;
                SELECT COUNT(*) INTO pending_orders_count FROM orders WHERE status = 'pending';
            END;

            -- 4. sp_RestockProduct
            DROP PROCEDURE IF EXISTS sp_RestockProduct;
            CREATE PROCEDURE sp_RestockProduct(
                IN parameter_product_id INT,
                IN parameter_quantity INT,
                IN parameter_admin_id INT
            )
            BEGIN
                IF parameter_quantity <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Quantity must be positive';
                END IF;

                START TRANSACTION;
                
                UPDATE products SET stock_quantity = stock_quantity + parameter_quantity WHERE id = parameter_product_id;
                
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, created_at, updated_at)
                VALUES (parameter_admin_id, 'PRODUCT_RESTOCKED', CONCAT('Restocked ', parameter_quantity, ' units'), 'App\\\\Models\\\\Product', parameter_product_id, NOW(), NOW());
                
                COMMIT;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessOrder;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_CancelOrder;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_GetAdminDashboardStats;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_RestockProduct;");
    }
};
