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
                IN p_user_id INT,
                IN p_shipping_address TEXT,
                IN p_payment_method VARCHAR(50),
                IN p_customer_name VARCHAR(255),
                IN p_customer_email VARCHAR(255),
                IN p_coupon_code VARCHAR(50),
                IN p_discount_amount DECIMAL(12,2),
                IN p_shipping_fee DECIMAL(10,2),
                IN p_notes TEXT,
                IN p_extra_packaging BOOLEAN,
                OUT p_order_id INT
            )
            BEGIN
                DECLARE v_cart_id INT;
                DECLARE v_subtotal DECIMAL(12,2);
                DECLARE v_total DECIMAL(12,2);
                DECLARE v_order_number VARCHAR(50);
                
                -- Error handling
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    RESIGNAL;
                END;

                START TRANSACTION;

                -- Get and Lock Cart
                SELECT id INTO v_cart_id FROM carts WHERE user_id = p_user_id FOR UPDATE;
                
                IF v_cart_id IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cart not found';
                END IF;

                -- Check Stock
                IF EXISTS (
                    SELECT 1 FROM cart_items ci 
                    JOIN products p ON ci.product_id = p.id 
                    WHERE ci.cart_id = v_cart_id AND p.stock_quantity < ci.quantity
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock for one or more items';
                END IF;

                -- Calculate Subtotal
                SELECT IFNULL(SUM(p.price * ci.quantity), 0) INTO v_subtotal
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = v_cart_id;

                SET v_total = v_subtotal - p_discount_amount + p_shipping_fee;

                -- Generate Order Number (Simple version)
                SET v_order_number = CONCAT('BSG-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(p_user_id, 4, '0'), '-', LPAD(FLOOR(RAND() * 1000), 3, '0'));

                -- Create Order
                INSERT INTO orders (
                    order_number, user_id, status, subtotal, discount_amount, 
                    shipping_fee, total_amount, coupon_code, customer_name, 
                    customer_email, shipping_address, payment_method, 
                    extra_packaging_requested, notes, placed_at, created_at, updated_at
                ) VALUES (
                    v_order_number, p_user_id, 'pending', v_subtotal, p_discount_amount,
                    p_shipping_fee, v_total, p_coupon_code, p_customer_name,
                    p_customer_email, p_shipping_address, p_payment_method,
                    p_extra_packaging, p_notes, NOW(), NOW(), NOW()
                );

                SET p_order_id = LAST_INSERT_ID();

                -- Move items to order_items
                INSERT INTO order_items (
                    order_id, product_id, product_name, product_brand, 
                    product_image, quantity, price, total, created_at, updated_at
                )
                SELECT 
                    p_order_id, ci.product_id, p.name, b.name, 
                    p.main_image, ci.quantity, p.price, (p.price * ci.quantity), NOW(), NOW()
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE ci.cart_id = v_cart_id;

                -- Update Stock
                UPDATE products p
                JOIN cart_items ci ON p.id = ci.product_id
                SET p.stock_quantity = p.stock_quantity - ci.quantity
                WHERE ci.cart_id = v_cart_id;

                -- Clean up cart
                DELETE FROM cart_items WHERE cart_id = v_cart_id;
                
                -- Handle Coupon usage
                IF p_coupon_code IS NOT NULL THEN
                    UPDATE coupons SET times_used = times_used + 1 WHERE code = p_coupon_code;
                END IF;

                COMMIT;
            END;
        ");

        // 2. View for Best Selling Products
        DB::unprepared("
            CREATE OR REPLACE VIEW vw_best_selling_products AS
            SELECT 
                p.id, p.name, b.name as brand_name, 
                SUM(oi.quantity) as total_sold,
                SUM(oi.total) as total_revenue,
                p.stock_quantity as current_stock
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE o.status != 'cancelled'
            GROUP BY p.id, p.name, b.name, p.stock_quantity
            ORDER BY total_sold DESC;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessOrder;");
        DB::unprepared("DROP VIEW IF EXISTS vw_best_selling_products;");
    }
};
