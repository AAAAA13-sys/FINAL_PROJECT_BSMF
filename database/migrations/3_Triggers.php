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
            -- 1. Product Audit Triggers
            DROP TRIGGER IF EXISTS trig_AuditProductInsert;
            CREATE TRIGGER trig_AuditProductInsert AFTER INSERT ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_CREATE', CONCAT('Created product: ', NEW.name), 'App\\\\Models\\\\Product', NEW.id, 
                JSON_OBJECT('name', NEW.name, 'casting', NEW.casting_name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
            END;

            DROP TRIGGER IF EXISTS trig_AuditProductUpdate;
            CREATE TRIGGER trig_AuditProductUpdate AFTER UPDATE ON products FOR EACH ROW
            BEGIN
                IF OLD.stock_quantity <> NEW.stock_quantity OR OLD.price <> NEW.price OR OLD.name <> NEW.name OR OLD.description <> NEW.description THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_UPDATE', CONCAT('Updated product: ', NEW.name), 'App\\\\Models\\\\Product', NEW.id, 
                    JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity),
                    JSON_OBJECT('name', NEW.name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
                END IF;
            END;

            DROP TRIGGER IF EXISTS trig_AuditProductDelete;
            CREATE TRIGGER trig_AuditProductDelete AFTER DELETE ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_DELETE', CONCAT('Deleted product: ', OLD.name), 'App\\\\Models\\\\Product', OLD.id, 
                JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity), @current_ip, NOW(), NOW());
            END;

            -- 2. Order Audit Triggers
            DROP TRIGGER IF EXISTS trig_AuditOrderUpdate;
            CREATE TRIGGER trig_AuditOrderUpdate AFTER UPDATE ON orders FOR EACH ROW
            BEGIN
                IF OLD.status <> NEW.status THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'ORDER_STATUS_UPDATE', CONCAT('Updated order #', NEW.order_number, ' status from ', OLD.status, ' to ', NEW.status), 
                    'App\\\\Models\\\\Order', NEW.id, JSON_OBJECT('status', OLD.status), JSON_OBJECT('status', NEW.status), @current_ip, NOW(), NOW());
                END IF;
            END;

            -- 3. Utility Triggers
            DROP TRIGGER IF EXISTS trig_LowStockAlert;
            CREATE TRIGGER trig_LowStockAlert AFTER UPDATE ON products FOR EACH ROW
            BEGIN
                IF NEW.stock_quantity <= NEW.low_stock_threshold AND OLD.stock_quantity > NEW.low_stock_threshold THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, created_at, updated_at)
                    VALUES (1, 'LOW_STOCK_ALERT', CONCAT('Low stock alert: ', NEW.name, ' (', NEW.stock_quantity, ' left)'), 'App\\\\Models\\\\Product', NEW.id, NOW(), NOW());
                END IF;
            END;

            -- 4. Coupon Audit Triggers
            DROP TRIGGER IF EXISTS trig_AuditCouponInsert;
            CREATE TRIGGER trig_AuditCouponInsert AFTER INSERT ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'COUPON_CREATE', CONCAT('Created coupon: ', NEW.coupon_code), 'App\\\\Models\\\\Coupon', NEW.id, 
                JSON_OBJECT('code', NEW.coupon_code, 'discount', NEW.discount_value), @current_ip, NOW(), NOW());
            END;

            DROP TRIGGER IF EXISTS trig_AuditCouponDelete;
            CREATE TRIGGER trig_AuditCouponDelete AFTER DELETE ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'COUPON_DELETE', CONCAT('Deleted coupon: ', OLD.coupon_code), 'App\\\\Models\\\\Coupon', OLD.id, 
                JSON_OBJECT('code', OLD.coupon_code, 'discount', OLD.discount_value), @current_ip, NOW(), NOW());
            END;

            -- 5. User Audit Triggers
            DROP TRIGGER IF EXISTS trig_AuditUserUpdate;
            CREATE TRIGGER trig_AuditUserUpdate AFTER UPDATE ON users FOR EACH ROW
            BEGIN
                IF OLD.role <> NEW.role THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'USER_ROLE_UPDATE', CONCAT('Updated user ', NEW.username, ' role from ', OLD.role, ' to ', NEW.role), 
                    'App\\\\Models\\\\User', NEW.id, JSON_OBJECT('role', OLD.role), JSON_OBJECT('role', NEW.role), @current_ip, NOW(), NOW());
                END IF;
            END;

            DROP TRIGGER IF EXISTS trig_AuditUserDelete;
            CREATE TRIGGER trig_AuditUserDelete AFTER DELETE ON users FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'USER_DELETE', CONCAT('Deleted user: ', OLD.username), 'App\\\\Models\\\\User', OLD.id, 
                JSON_OBJECT('username', OLD.username, 'email', OLD.email), @current_ip, NOW(), NOW());
            END;

            -- 6. Taxonomy Audit Triggers (Brand, Scale, Series)
            DROP TRIGGER IF EXISTS trig_AuditBrandInsert;
            CREATE TRIGGER trig_AuditBrandInsert AFTER INSERT ON brands FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'BRAND_CREATE', CONCAT('Created brand: ', NEW.name), 'App\\\\Models\\\\Brand', NEW.id, 
                JSON_OBJECT('name', NEW.name), @current_ip, NOW(), NOW());
            END;

            DROP TRIGGER IF EXISTS trig_AuditScaleInsert;
            CREATE TRIGGER trig_AuditScaleInsert AFTER INSERT ON scales FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'SCALE_CREATE', CONCAT('Created scale: ', NEW.name), 'App\\\\Models\\\\Scale', NEW.id, 
                JSON_OBJECT('name', NEW.name), @current_ip, NOW(), NOW());
            END;

            DROP TRIGGER IF EXISTS trig_AuditSeriesInsert;
            CREATE TRIGGER trig_AuditSeriesInsert AFTER INSERT ON series FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'SERIES_CREATE', CONCAT('Created series: ', NEW.name), 'App\\\\Models\\\\Series', NEW.id, 
                JSON_OBJECT('name', NEW.name), @current_ip, NOW(), NOW());
            END;
        ");
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
        DB::unprepared("DROP TRIGGER IF EXISTS trig_LowStockAlert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditCouponInsert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditCouponDelete;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditUserUpdate;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditUserDelete;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditBrandInsert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditScaleInsert;");
        DB::unprepared("DROP TRIGGER IF EXISTS trig_AuditSeriesInsert;");
    }
};
