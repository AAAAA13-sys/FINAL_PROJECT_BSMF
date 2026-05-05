<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer');
            $table->string('phone', 20)->nullable();
            $table->text('default_shipping_address')->nullable();
            $table->timestamps();
        });

        // 2. Sessions Table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 3. Audit Logs Table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('action', 50);
            $table->text('description');
            $table->string('model_type')->nullable();
            $table->unsignedInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 4. Brands Table
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // 5. Scales Table
        Schema::create('scales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. Series Table
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->integer('year')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        // 7. Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('scale_id');
            $table->unsignedInteger('series_id')->nullable();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('scale_id')->references('id')->on('scales')->onDelete('cascade');
            $table->foreign('series_id')->references('id')->on('series')->onDelete('set null');
            
            $table->string('name', 200);
            $table->string('casting_name', 200);
            $table->string('slug', 220)->unique();
            $table->integer('year')->nullable();
            $table->string('color', 50)->nullable();
            $table->boolean('is_treasure_hunt')->default(false);
            $table->boolean('is_super_treasure_hunt')->default(false);
            $table->boolean('is_chase')->default(false);
            $table->boolean('is_rlc_exclusive')->default(false);
            $table->string('card_condition', 20)->default('mint');
            $table->boolean('is_carded')->default(true);
            $table->boolean('is_loose')->default(false);
            $table->decimal('price', 12, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('main_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->longText('description')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Product Images Table
        Schema::create('product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('image_path');
            $table->string('type', 50)->default('gallery');
            $table->timestamps();
        });

        // 9. Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code', 50)->unique();
            $table->string('name', 100);
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping', 'bogo'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('times_used')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 10. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number', 50)->unique();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('coupon_code', 50)->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('shipping_address');
            $table->string('payment_method', 50);
            $table->boolean('extra_packaging_requested')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // 11. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_brand')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });

        // 12. Carts Table
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 13. Cart Items Table
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('product_id');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_time', 12, 2)->nullable();
            $table->timestamps();
        });

        // 14. Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();
        });

        // 15. SQL Constraints
        DB::unprepared("
            ALTER TABLE products ADD CONSTRAINT check_price_non_negative CHECK (price >= 0);
            ALTER TABLE products ADD CONSTRAINT check_stock_non_negative CHECK (stock_quantity >= 0);
        ");

        // 16. Stored Procedures (Consolidated & Robust)
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

                SELECT COUNT(*) INTO @product_lock_count 
                FROM products 
                JOIN cart_items ON products.id = cart_items.product_id 
                WHERE cart_items.cart_id = variable_cart_id FOR UPDATE;

                IF EXISTS (
                    SELECT 1 FROM cart_items 
                    JOIN products ON cart_items.product_id = products.id 
                    WHERE cart_items.cart_id = variable_cart_id AND products.stock_quantity < cart_items.quantity
                ) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock for one or more items';
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
                LEFT JOIN brands ON products.brand_id = brands.id
                WHERE cart_items.cart_id = variable_cart_id;

                UPDATE products
                JOIN cart_items ON products.id = cart_items.product_id
                SET products.stock_quantity = products.stock_quantity - cart_items.quantity
                WHERE cart_items.cart_id = variable_cart_id;

                DELETE FROM cart_items WHERE cart_id = variable_cart_id;
                
                IF parameter_coupon_code IS NOT NULL AND parameter_coupon_code != '' THEN
                    SELECT id INTO @coupon_id FROM coupons 
                    WHERE coupon_code = parameter_coupon_code 
                    FOR UPDATE;
                    
                    IF @coupon_id IS NULL THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid coupon code';
                    END IF;
                    
                    SELECT is_active, expires_at, usage_limit, times_used INTO @is_active, @expires_at, @usage_limit, @times_used 
                    FROM coupons WHERE id = @coupon_id;
                    
                    IF @is_active = 0 OR (@expires_at IS NOT NULL AND @expires_at < NOW()) OR (@usage_limit IS NOT NULL AND @times_used >= @usage_limit) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Coupon is no longer valid';
                    END IF;
                    
                    IF EXISTS (
                        SELECT 1 FROM orders 
                        WHERE user_id = parameter_user_id 
                        AND coupon_code = parameter_coupon_code 
                        AND status != 'cancelled'
                    ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You have already used this coupon';
                    END IF;
                    
                    UPDATE coupons SET times_used = times_used + 1 WHERE id = @coupon_id;
                END IF;

                COMMIT;
            END;
        ");

        // 17. Audit Triggers
        DB::unprepared("
            CREATE TRIGGER trig_AuditProductInsert AFTER INSERT ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (@current_user_id, 'PRODUCT_CREATED', CONCAT('Created product: ', NEW.name), 'App\\\\Models\\\\Product', NEW.id, 
                JSON_OBJECT('name', NEW.name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
            END;

            CREATE TRIGGER trig_AuditProductUpdate AFTER UPDATE ON products FOR EACH ROW
            BEGIN
                IF OLD.stock_quantity <> NEW.stock_quantity OR OLD.price <> NEW.price OR OLD.name <> NEW.name THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (@current_user_id, 'PRODUCT_UPDATED', CONCAT('Updated product: ', NEW.name), 'App\\\\Models\\\\Product', NEW.id, 
                    JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity),
                    JSON_OBJECT('name', NEW.name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
                END IF;
            END;

            CREATE TRIGGER trig_AuditProductDelete AFTER DELETE ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (@current_user_id, 'PRODUCT_DELETED', CONCAT('Deleted product: ', OLD.name), 'App\\\\Models\\\\Product', OLD.id, 
                JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity), @current_ip, NOW(), NOW());
            END;

            CREATE TRIGGER trig_AuditOrderUpdate AFTER UPDATE ON orders FOR EACH ROW
            BEGIN
                IF OLD.status <> NEW.status THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (@current_user_id, 'ORDER_STATUS_UPDATE', CONCAT('Updated order #', NEW.order_number, ' status from ', OLD.status, ' to ', NEW.status), 
                    'App\\\\Models\\\\Order', NEW.id, JSON_OBJECT('status', OLD.status), JSON_OBJECT('status', NEW.status), @current_ip, NOW(), NOW());
                END IF;
            END;

            CREATE TRIGGER trig_AuditCouponInsert AFTER INSERT ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (@current_user_id, 'COUPON_CREATED', CONCAT('Created coupon: ', NEW.coupon_code), 'App\\\\Models\\\\Coupon', NEW.id, 
                JSON_OBJECT('code', NEW.coupon_code, 'discount', NEW.discount_value), @current_ip, NOW(), NOW());
            END;

            CREATE TRIGGER trig_AuditUserDelete AFTER DELETE ON users FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (@current_user_id, 'USER_DELETED', CONCAT('Deleted user: ', OLD.username), 'App\\\\Models\\\\User', OLD.id, 
                JSON_OBJECT('username', OLD.username, 'email', OLD.email), @current_ip, NOW(), NOW());
            END;

            CREATE TRIGGER trig_AuditCouponDelete AFTER DELETE ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (@current_user_id, 'COUPON_DELETED', CONCAT('Deleted coupon: ', OLD.coupon_code), 'App\\\\Models\\\\Coupon', OLD.id, 
                JSON_OBJECT('code', OLD.coupon_code, 'discount', OLD.discount_value), @current_ip, NOW(), NOW());
            END;
        ");

        // 18. Seed Data
        DB::table('users')->insert([
            [
                'name' => 'BSMF Admin',
                'username' => 'admin',
                'email' => 'admin@bsmfgarage.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garage Staff',
                'username' => 'staff',
                'email' => 'staff@bsmfgarage.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Collector',
                'username' => 'john',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('coupons')->insert([
            'coupon_code' => 'WELCOME10',
            'name' => 'First Acquisition Gift',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_order_amount' => 0.00,
            'usage_limit' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditProductInsert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditProductUpdate;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditProductDelete;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditOrderUpdate;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditCouponInsert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditUserDelete;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditCouponDelete;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessOrder;");
        
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('series');
        Schema::dropIfExists('scales');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
