-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: final_project_bsmf
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'PRODUCT_CREATE','Created product: Super Treasure Hunt Subaru Impreza STI','App\\Models\\Product',1,NULL,'{\"name\": \"Super Treasure Hunt Subaru Impreza STI\", \"casting\": \"Subaru Impreza STI\", \"price\": 1999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(2,1,'PRODUCT_CREATE','Created product: Super Treasure Hunt Cruella De Vill','App\\Models\\Product',2,NULL,'{\"name\": \"Super Treasure Hunt Cruella De Vill\", \"casting\": \"Cruella De Vill\", \"price\": 999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(3,1,'PRODUCT_CREATE','Created product: Super Treasure Hunt \'20 Dodge Charger Hellcat','App\\Models\\Product',3,NULL,'{\"name\": \"Super Treasure Hunt \'20 Dodge Charger Hellcat\", \"casting\": \"\'20 Dodge Charger Hellcat\", \"price\": 1499.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(4,1,'PRODUCT_CREATE','Created product: Hotwheels Premium LBWK McLaren 720s','App\\Models\\Product',4,NULL,'{\"name\": \"Hotwheels Premium LBWK McLaren 720s\", \"casting\": \"McLaren 720s\", \"price\": 399.00, \"stock\": 5}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(5,1,'PRODUCT_CREATE','Created product: Hotwheels Premium Forza Nissan Silvia S15','App\\Models\\Product',5,NULL,'{\"name\": \"Hotwheels Premium Forza Nissan Silvia S15\", \"casting\": \"Nissan Silvia S15\", \"price\": 799.00, \"stock\": 5}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(6,1,'PRODUCT_CREATE','Created product: Hotwheels Premium RWB Porsche 930','App\\Models\\Product',6,NULL,'{\"name\": \"Hotwheels Premium RWB Porsche 930\", \"casting\": \"HOTWHEELS PREMIUM RWB PORSCHE 930_\", \"price\": 2999.00, \"stock\": 0}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(7,1,'PRODUCT_CREATE','Created product: Fast and Furious \"WOF\" Honda S2000 Suki','App\\Models\\Product',7,NULL,'{\"name\": \"Fast and Furious \\\"WOF\\\" Honda S2000 Suki\", \"casting\": \"HOTWHEELS FAST AND FURIOS HONDA S2000 _SUKI_\", \"price\": 1499.00, \"stock\": 0}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(8,1,'PRODUCT_CREATE','Created product: Porsche 911 GT3 RS \"ID\"','App\\Models\\Product',8,NULL,'{\"name\": \"Porsche 911 GT3 RS \\\"ID\\\"\", \"casting\": \"HOTWHEELS ID PORSCHE 911 GT3 RS\", \"price\": 1999.00, \"stock\": 0}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(9,1,'PRODUCT_CREATE','Created product: Hotwheels Premium RWB Porsche 930','App\\Models\\Product',9,NULL,'{\"name\": \"Hotwheels Premium RWB Porsche 930\", \"casting\": \"HOTWHEELS PREMIUM RWB PORSCHE 930_\", \"price\": 2999.00, \"stock\": 0}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(10,1,'PRODUCT_CREATE','Created product: Legends Tour Honda S2000','App\\Models\\Product',10,NULL,'{\"name\": \"Legends Tour Honda S2000\", \"casting\": \"LEGENDS TOUR HONDA S2000\", \"price\": 1299.00, \"stock\": 0}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(11,1,'PRODUCT_CREATE','Created product: MSCHF x Hotwheels Collaboration \"Not wheels\"','App\\Models\\Product',11,NULL,'{\"name\": \"MSCHF x Hotwheels Collaboration \\\"Not wheels\\\"\", \"casting\": \"MSCHF X HOTWHEELS COLLAB _NOT WHEELS_\", \"price\": 5000.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(12,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) \'90 Acura NSX','App\\Models\\Product',12,NULL,'{\"name\": \"STH (Super Treasure Hunt) \'90 Acura NSX\", \"casting\": \"STH ACURA NSX\", \"price\": 3499.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(13,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) 1975 Datsun Sunny Truck (B120)','App\\Models\\Product',13,NULL,'{\"name\": \"STH (Super Treasure Hunt) 1975 Datsun Sunny Truck (B120)\", \"casting\": \"STH DATSUN SUNNY TRUCK\", \"price\": 1999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(14,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) \'20 Dodge Charger Hellcat','App\\Models\\Product',14,NULL,'{\"name\": \"STH (Super Treasure Hunt) \'20 Dodge Charger Hellcat\", \"casting\": \"STH DODGE CHARGER HELLCAT\", \"price\": 2999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(15,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) Ford RS200 Gulf','App\\Models\\Product',15,NULL,'{\"name\": \"STH (Super Treasure Hunt) Ford RS200 Gulf\", \"casting\": \"STH FORD RS200 GULF\", \"price\": 1499.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(16,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) Glory Chaser Gulf','App\\Models\\Product',16,NULL,'{\"name\": \"STH (Super Treasure Hunt) Glory Chaser Gulf\", \"casting\": \"STH GLORY CHASER GULF\", \"price\": 999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(17,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) Mazda 787B','App\\Models\\Product',17,NULL,'{\"name\": \"STH (Super Treasure Hunt) Mazda 787B\", \"casting\": \"STH MAZDA 787B\", \"price\": 2499.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(18,1,'PRODUCT_CREATE','Created product: STH (Super Treasure Hunt) \'71 Mustang Funny Car','App\\Models\\Product',18,NULL,'{\"name\": \"STH (Super Treasure Hunt) \'71 Mustang Funny Car\", \"casting\": \"STH MUSTANG FUNNY CAR_\", \"price\": 1499.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(19,1,'PRODUCT_CREATE','Created product: Hotwheels \"Eggclusive\" Honda Civic SI','App\\Models\\Product',19,NULL,'{\"name\": \"Hotwheels \\\"Eggclusive\\\" Honda Civic SI\", \"casting\": \"WALMART EGGSCLUCIVE HONDA CIVIC SI\", \"price\": 4999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(20,1,'PRODUCT_CREATE','Created product: Zamac Walmart Exclusive Porsche 911 GT3 rs','App\\Models\\Product',20,NULL,'{\"name\": \"Zamac Walmart Exclusive Porsche 911 GT3 rs\", \"casting\": \"ZAMAC WALMART EXCLUSIVE PORSCHE 911 GT3 RS\", \"price\": 1999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(21,1,'PRODUCT_CREATE','Created product: Zamac Target Exclusive Porsche 934.5','App\\Models\\Product',21,NULL,'{\"name\": \"Zamac Target Exclusive Porsche 934.5\", \"casting\": \"ZAMAC WALMART EXCLUSIVE PORSCHE 934.5\", \"price\": 999.00, \"stock\": 10}',NULL,'2026-05-15 00:08:04','2026-05-15 00:08:04'),(22,1,'LOGIN','User logged in: admin',NULL,NULL,NULL,NULL,'127.0.0.1','2026-05-14 16:08:15','2026-05-14 16:08:15'),(23,1,'ACTION_POST','Performed POST on login',NULL,NULL,NULL,'{\"email\":\"admin\"}','127.0.0.1','2026-05-14 16:08:15','2026-05-14 16:08:15'),(24,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:08:16','2026-05-14 16:08:16'),(25,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:10:09','2026-05-14 16:10:09'),(26,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:14:21','2026-05-14 16:14:21'),(27,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:14:23','2026-05-14 16:14:23'),(28,1,'LOGIN','User logged in: admin',NULL,NULL,NULL,NULL,'127.0.0.1','2026-05-14 16:16:13','2026-05-14 16:16:13'),(29,1,'ACTION_POST','Performed POST on login',NULL,NULL,NULL,'{\"email\":\"admin\"}','127.0.0.1','2026-05-14 16:16:13','2026-05-14 16:16:13'),(30,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:16:15','2026-05-14 16:16:15'),(31,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:18:26','2026-05-14 16:18:26'),(32,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','127.0.0.1','2026-05-14 16:18:36','2026-05-14 16:18:36'),(33,1,'LOGIN','User logged in: admin',NULL,NULL,NULL,NULL,'112.201.184.46','2026-05-15 07:11:45','2026-05-15 07:11:45'),(34,1,'ACTION_POST','Performed POST on login',NULL,NULL,NULL,'{\"email\":\"admin\"}','112.201.184.46','2026-05-15 07:11:46','2026-05-15 07:11:46'),(35,1,'PAGE_VISIT','Visited page: admin/dashboard',NULL,NULL,NULL,'[]','112.201.184.46','2026-05-15 07:11:47','2026-05-15 07:11:47'),(36,1,'PAGE_VISIT','Visited page: admin/users',NULL,NULL,NULL,'[]','112.201.184.46','2026-05-15 07:11:51','2026-05-15 07:11:51'),(37,1,'LOGOUT','User logged out: admin',NULL,NULL,NULL,NULL,'112.201.184.46','2026-05-15 07:12:00','2026-05-15 07:12:00');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Hot Wheels','hot-wheels','Mattel Premium Die-Cast',NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_at_time` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (1,1,21,1,999.00,'2026-05-15 07:13:17','2026-05-15 07:13:17');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,5,'2026-05-15 07:13:17','2026-05-15 07:13:17');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `discount_type` enum('percentage','fixed','free_shipping') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) DEFAULT NULL,
  `min_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(12,2) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `times_used` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_coupon_code_unique` (`coupon_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'WELCOME10','First Acquisition Gift','percentage',10.00,0.00,NULL,NULL,NULL,0,1,'2026-05-14 16:08:02','2026-05-14 16:08:02');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditCouponInsert AFTER INSERT ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'COUPON_CREATE', CONCAT('Created coupon: ', NEW.coupon_code), 'App\\Models\\Coupon', NEW.id, 
                JSON_OBJECT('code', NEW.coupon_code, 'discount', NEW.discount_value), @current_ip, NOW(), NOW());
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditCouponDelete AFTER DELETE ON coupons FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'COUPON_DELETE', CONCAT('Deleted coupon: ', OLD.coupon_code), 'App\\Models\\Coupon', OLD.id, 
                JSON_OBJECT('code', OLD.coupon_code, 'discount', OLD.discount_value), @current_ip, NOW(), NOW());
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'1_Schema',1),(2,'2_Procedures',1),(3,'3_Triggers',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_brand` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,'Super Treasure Hunt Subaru Impreza STI','Hot Wheels','images/products/subaru-impreza-sti/main.jpg',1,1999.00,1999.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,1,3,'Super Treasure Hunt \'20 Dodge Charger Hellcat','Hot Wheels','images/products/dodge-charger-hellcat/main.jpg',1,1499.00,1499.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(3,1,5,'Hotwheels Premium Forza Nissan Silvia S15','Hot Wheels','images/products/nissan-silvia-s15/main.jpg',1,799.00,799.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(4,2,1,'Super Treasure Hunt Subaru Impreza STI','Hot Wheels','images/products/subaru-impreza-sti/main.jpg',1,1999.00,1999.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(5,2,4,'Hotwheels Premium LBWK McLaren 720s','Hot Wheels','images/products/mclaren-720s/main.jpg',1,399.00,399.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(6,2,5,'Hotwheels Premium Forza Nissan Silvia S15','Hot Wheels','images/products/nissan-silvia-s15/main.jpg',1,799.00,799.00,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(7,3,5,'Hotwheels Premium Forza Nissan Silvia S15','Hot Wheels','images/products/nissan-silvia-s15/main.jpg',1,799.00,799.00,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `extra_packaging_requested` tinyint(1) NOT NULL DEFAULT 0,
  `courier_name` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `tracking_link` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `placed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'BSG-20260515-0003-208',3,'delivered',4297.00,0.00,0.00,4297.00,NULL,'John Doe','john@example.com',NULL,'123 Collector Ave, Manila','credit_card',0,NULL,NULL,NULL,NULL,'2026-05-04 16:08:04',NULL,NULL,'2026-05-12 16:08:04',NULL,NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,'BSG-20260515-0004-250',4,'delivered',3197.00,0.00,0.00,3197.00,NULL,'Jane Smith','jane@example.com',NULL,'456 Racer St, Cebu','paypal',0,NULL,NULL,NULL,NULL,'2026-05-06 16:08:04',NULL,NULL,'2026-05-13 16:08:04',NULL,NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(3,'BSG-20260515-0005-838',5,'delivered',3297.00,0.00,0.00,3297.00,NULL,'Mike Racer','mike@example.com',NULL,'789 Diecast Blvd, Davao','bank_transfer',0,NULL,NULL,NULL,NULL,'2026-05-02 16:08:04',NULL,NULL,'2026-05-10 16:08:04',NULL,NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditOrderUpdate AFTER UPDATE ON orders FOR EACH ROW
            BEGIN
                IF OLD.status <> NEW.status THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'ORDER_STATUS_UPDATE', CONCAT('Updated order #', NEW.order_number, ' status from ', OLD.status, ' to ', NEW.status), 
                    'App\\Models\\Order', NEW.id, JSON_OBJECT('status', OLD.status), JSON_OBJECT('status', NEW.status), @current_ip, NOW(), NOW());
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'gallery',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'images/products/subaru-impreza-sti/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,1,'images/products/subaru-impreza-sti/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(3,1,'images/products/subaru-impreza-sti/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(4,1,'images/products/subaru-impreza-sti/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(5,2,'images/products/cruella-de-vill/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(6,2,'images/products/cruella-de-vill/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(7,2,'images/products/cruella-de-vill/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(8,2,'images/products/cruella-de-vill/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(9,3,'images/products/dodge-charger-hellcat/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(10,3,'images/products/dodge-charger-hellcat/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(11,3,'images/products/dodge-charger-hellcat/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(12,3,'images/products/dodge-charger-hellcat/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(13,4,'images/products/mclaren-720s/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(14,4,'images/products/mclaren-720s/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(15,4,'images/products/mclaren-720s/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(16,4,'images/products/mclaren-720s/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(17,5,'images/products/nissan-silvia-s15/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(18,5,'images/products/nissan-silvia-s15/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(19,5,'images/products/nissan-silvia-s15/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(20,5,'images/products/nissan-silvia-s15/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(21,6,'images/products/hotwheels-premium-rwb-porsche-930/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(22,6,'images/products/hotwheels-premium-rwb-porsche-930/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(23,6,'images/products/hotwheels-premium-rwb-porsche-930/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(24,6,'images/products/hotwheels-premium-rwb-porsche-930/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(25,8,'images/products/porsche-911-gt3-rs-id/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(26,8,'images/products/porsche-911-gt3-rs-id/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(27,8,'images/products/porsche-911-gt3-rs-id/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(28,8,'images/products/porsche-911-gt3-rs-id/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(29,9,'images/products/rwb-porsche-930/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(30,9,'images/products/rwb-porsche-930/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(31,9,'images/products/rwb-porsche-930/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(32,9,'images/products/rwb-porsche-930/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(33,15,'images/products/ford-rs200-gulf/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(34,15,'images/products/ford-rs200-gulf/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(35,15,'images/products/ford-rs200-gulf/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(36,15,'images/products/ford-rs200-gulf/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(37,16,'images/products/glory-chaser-gulf/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(38,16,'images/products/glory-chaser-gulf/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(39,16,'images/products/glory-chaser-gulf/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(40,16,'images/products/glory-chaser-gulf/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(41,21,'images/products/zamac-target-exclusive-porsche-9345/gallery-1.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(42,21,'images/products/zamac-target-exclusive-porsche-9345/gallery-2.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(43,21,'images/products/zamac-target-exclusive-porsche-9345/gallery-3.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04'),(44,21,'images/products/zamac-target-exclusive-porsche-9345/gallery-4.jpg','gallery','2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `scale_id` int(10) unsigned NOT NULL,
  `series_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `casting_name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `is_treasure_hunt` tinyint(1) NOT NULL DEFAULT 0,
  `is_super_treasure_hunt` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_chase` tinyint(1) NOT NULL DEFAULT 0,
  `is_rlc_exclusive` tinyint(1) NOT NULL DEFAULT 0,
  `card_condition` varchar(20) NOT NULL DEFAULT 'mint',
  `is_carded` tinyint(1) NOT NULL DEFAULT 1,
  `is_loose` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(12,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `main_image` varchar(255) DEFAULT NULL,
  `additional_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_images`)),
  `description` longtext DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_scale_id_foreign` (`scale_id`),
  KEY `products_series_id_foreign` (`series_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_scale_id_foreign` FOREIGN KEY (`scale_id`) REFERENCES `scales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE SET NULL,
  CONSTRAINT `check_price_non_negative` CHECK (`price` >= 0),
  CONSTRAINT `check_stock_non_negative` CHECK (`stock_quantity` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,1,'Super Treasure Hunt Subaru Impreza STI','Subaru Impreza STI','subaru-impreza-sti',2026,NULL,0,1,1,0,0,'Loose Pack',0,1,1999.00,10,5,'images/products/subaru-impreza-sti/main.jpg',NULL,'The Super Treasure Hunt Subaru Impreza STI was recently released by hotwheels on the newly released J case 2026. The Super Treasure Hunt have the traditional Subaru Rally Livery in a spectraflame blue and a golden yellow tires.\n\nFun Fact: Super Treasure Hunts have rubber tires. There is only one exception on this which is the Custom otto Super Treasure Hunt.\n\nCondition: Loose Pack',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,1,1,1,'Super Treasure Hunt Cruella De Vill','Cruella De Vill','cruella-de-vill',NULL,NULL,0,1,1,0,0,'Loose Pack',0,1,999.00,10,5,'images/products/cruella-de-vill/main.jpg',NULL,'The Super Treasure Hunt Cruella De Vill was one of the oldest Super Treasure Hunt released by hotwheels. It is a disney collaboration exclusive which makes it very sought after and hard to find nowadays.\n\nFun Fact: Super Treasure Hunts Have a \"TH\" logo on the car. This separates it from the normal/basic version.\n\nCondition: Loose Pack',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(3,1,1,1,'Super Treasure Hunt \'20 Dodge Charger Hellcat','\'20 Dodge Charger Hellcat','dodge-charger-hellcat',2026,NULL,0,1,1,0,0,'Loose Pack',0,1,1499.00,10,5,'images/products/dodge-charger-hellcat/main.jpg',NULL,'The Super Treasure Hunt \'20 Dodge Charger Was released as the Super Treasure Hunt of the 2026 P case of hotwheels. It is a spectraflame pink with a clean finish on the decals. One of the most sought after and most expensive Super Treasure Hunt of the 2025 Batch\n\nFun fact: Super Treasure Hunts Are Painted in spectraflame and are very sought after by the collectors.\n\nCondition: Loose Pack',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(4,1,1,2,'Hotwheels Premium LBWK McLaren 720s','McLaren 720s','mclaren-720s',NULL,NULL,0,0,0,0,0,'Loose Pack',0,1,399.00,5,5,'images/products/mclaren-720s/main.jpg',NULL,'The Premium Hotwheels LBWK (Liberty walk) McLaren 720s was one of the most recent releases of hotwheels premium from the \"silhouette\" set. This model contains the usual aggressive body kits of LBWK with the deep rims.\n\nFun Fact: Premium Hotwheels have rubber tires. They are called \"Premium\" for a reason. It is much expensive compared to the 129 Pesos we see on the mall.\n\nCondition: Loose Pack',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(5,1,1,2,'Hotwheels Premium Forza Nissan Silvia S15','Nissan Silvia S15','nissan-silvia-s15',NULL,NULL,0,0,0,0,0,'Loose Pack',0,1,799.00,5,5,'images/products/nissan-silvia-s15/main.jpg',NULL,'The Premium Forza Nissan Silvia S15 was released in a box set and individual blister packaging. It is one of the hardest to find forza cars and it is much expensive compared to other premium cars due to it\'s demand. This Premium consist of the Forza livery in sea blue with a yellow 6 spoke wheels.\n\nFun Fact: Premium Hotwheels have metal base making it much heavier than normal hotwheels.\n\nCondition: Loose Pack',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(6,1,1,2,'Hotwheels Premium RWB Porsche 930','HOTWHEELS PREMIUM RWB PORSCHE 930_','hotwheels-premium-rwb-porsche-930',2025,NULL,0,0,1,0,0,'Mint',1,0,2999.00,0,5,'images/products/hotwheels-premium-rwb-porsche-930/main.jpg',NULL,'First released in 2018, this specific RWB Model is the 2nd most expensive \"Premium\" carded of RWB. The value of this specific model has already reached 10x its value when it was first released. It is a porsche 930 in a RWB kit, which makes it sought after.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(7,1,1,NULL,'Fast and Furious \"WOF\" Honda S2000 Suki','HOTWHEELS FAST AND FURIOS HONDA S2000 _SUKI_','fast-and-furious-wof-honda-s2000-suki',2025,NULL,0,0,1,0,0,'Mint',1,0,1499.00,0,5,'images/products/fast-and-furious-wof-honda-s2000-suki/main.jpg',NULL,'Pink is the new meta. Suki is one of the hottest things right now in the market because of its color and origin. This car was originally from the Fast and Furious movie, making the demand from the collectors high. This is just a silver series but the price is very high for a normal Silver series.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(8,1,1,NULL,'Porsche 911 GT3 RS \"ID\"','HOTWHEELS ID PORSCHE 911 GT3 RS','porsche-911-gt3-rs-id',2025,NULL,0,0,1,0,0,'Mint',1,0,1999.00,0,5,'images/products/porsche-911-gt3-rs-id/main.jpg',NULL,'This was already discounted Years ago. Hotwheels ID are a unique set of collectible series of hotwheels, wherein you can use the actual vehicle on the hotwheels app (Already discontinued). You can only acquire this years ago, making it expensive and hard to find. It is covered in spectraflame Gold with a unique set of tires.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(9,1,1,2,'Hotwheels Premium RWB Porsche 930','HOTWHEELS PREMIUM RWB PORSCHE 930_','rwb-porsche-930',2025,NULL,0,0,1,0,0,'Mint',1,0,2999.00,0,5,'images/products/rwb-porsche-930/main.jpg',NULL,'First released in 2018, this specific RWB Model is the 2nd most expensive \"Premium\" carded of RWB. The value of this specific model has already reached 10x its value when it was first released. It is a porsche 930 in a RWB kit, which makes it sought after.\n\nCondition: Mint',1,1,'2026-05-14 16:08:04','2026-05-15 06:48:01'),(10,1,1,NULL,'Legends Tour Honda S2000','LEGENDS TOUR HONDA S2000','legends-tour-honda-s2000',2025,NULL,0,0,1,0,0,'Mint',1,0,1299.00,0,5,'images/products/legends-tour-honda-s2000/main.jpg',NULL,'The vintage AEM Livery from the STH honda S2000 is now available for the legends tour release. This variant features A rubber tires and a spectraflame violet finish similar to it\'s STH variant (A blue one)\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(11,1,1,NULL,'MSCHF x Hotwheels Collaboration \"Not wheels\"','MSCHF X HOTWHEELS COLLAB _NOT WHEELS_','mschf-x-hotwheels-collaboration-not-wheels',2025,NULL,0,0,1,0,0,'Mint/Complete',1,0,5000.00,10,5,'images/products/mschf-x-hotwheels-collaboration-not-wheels/main.jpg',NULL,'A collaboration with mschf is something collectors are dying for. Not wheels is an hotwheels model that seems unfinished, but it is as is. This is a very rare piece and you can only acquire one of this through Mattel\'s official website.\n\nCondition: Mint/Complete',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(12,1,1,1,'STH (Super Treasure Hunt) \'90 Acura NSX','STH ACURA NSX','90-acura-nsx',2025,NULL,0,1,1,0,0,'Mint',1,0,3499.00,10,5,'images/products/90-acura-nsx/main.jpg',NULL,'one of the oldest JDM STH ever released. Iconic spectraflame blue with the 5 spoke chrome wheels. NSX isn\'t just a normal JDM. It was the Supercar killer of the 90\'s.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(13,1,1,1,'STH (Super Treasure Hunt) 1975 Datsun Sunny Truck (B120)','STH DATSUN SUNNY TRUCK','1975-datsun-sunny-truck-b120',2025,NULL,0,1,1,0,0,'Mint',1,0,1999.00,10,5,'images/products/1975-datsun-sunny-truck-b120/main.jpg',NULL,'Sunnyyyyyyy... This STH was released on the D case of 2025. Spectraflame blue and Golden wheels is what makes this STH Special. It is really hard to find nowadays.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(14,1,1,1,'STH (Super Treasure Hunt) \'20 Dodge Charger Hellcat','STH DODGE CHARGER HELLCAT','20-dodge-charger-hellcat',2025,NULL,0,1,1,0,0,'Mint',1,0,2999.00,10,5,'images/products/20-dodge-charger-hellcat/main.jpg',NULL,'Spectraflame pink Hellcat is the dream collection of many collectors nowadays. The iconic hellcat in a pink color is something else. This was released as The P case STH of 2025.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(15,1,1,1,'STH (Super Treasure Hunt) Ford RS200 Gulf','STH FORD RS200 GULF','ford-rs200-gulf',2025,NULL,0,1,1,0,0,'Mint',1,0,1499.00,10,5,'images/products/ford-rs200-gulf/main.jpg',NULL,'This was released as the last STH of the year 2025. It is a super treasure hunt covered in the iconic Gulf livery with orange wheels. One of the most sought after livery in any die-cast models\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(16,1,1,1,'STH (Super Treasure Hunt) Glory Chaser Gulf','STH GLORY CHASER GULF','glory-chaser-gulf',2025,NULL,0,1,1,0,0,'Mint',1,0,999.00,10,5,'images/products/glory-chaser-gulf/main.jpg',NULL,'This Super Treasure Hunt was released years ago. It is the iconic Glory chaser in a gulf livery, making it one of the most sought after sth of it\'s year.\n\nCondition: Mint',1,1,'2026-05-14 16:08:04','2026-05-15 06:47:24'),(17,1,1,1,'STH (Super Treasure Hunt) Mazda 787B','STH MAZDA 787B','mazda-787b',2025,NULL,0,1,1,0,0,'Mint',1,0,2499.00,10,5,'images/products/mazda-787b/main.jpg',NULL,'One of the most iconic le mans car ever. The 787B isn\'t just an ordinary car. It is the standard for dominance. This sth was featured on the 2024 Set with the renown livery.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(18,1,1,1,'STH (Super Treasure Hunt) \'71 Mustang Funny Car','STH MUSTANG FUNNY CAR_','71-mustang-funny-car',2025,NULL,0,1,1,0,0,'Mint',1,0,1499.00,10,5,'images/products/71-mustang-funny-car/main.jpg',NULL,'Spectraflame red, Goodyear wheels, Dragster stance? This car is for you. The mustang funny car was a part of the 2025 STH set which makes it a recent release.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(19,1,1,NULL,'Hotwheels \"Eggclusive\" Honda Civic SI','WALMART EGGSCLUCIVE HONDA CIVIC SI','hotwheels-eggclusive-honda-civic-si',2025,NULL,0,0,1,0,0,'Mint',1,0,4999.00,10,5,'images/products/hotwheels-eggclusive-honda-civic-si/main.jpg',NULL,'One of the grails for every Civic SI collector. This isn\'t just a normal grail, this is a hard to find grail. Eggclusive was a very exclusive release by hotwheels below 2010\'s which makes this civic si model one of the hardest to find. You can\'t just buy these nowadays. The supply is very limited and most likely, all quantities are already in the collectors hand.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(20,1,1,NULL,'Zamac Walmart Exclusive Porsche 911 GT3 rs','ZAMAC WALMART EXCLUSIVE PORSCHE 911 GT3 RS','zamac-walmart-exclusive-porsche-911-gt3-rs',2025,NULL,0,0,1,0,0,'Mint',1,0,1999.00,10,5,'images/products/zamac-walmart-exclusive-porsche-911-gt3-rs/main.jpg',NULL,'GT3 rs is one of the most in demand casting of hotwheels. This being a zamac release is something special. You can only acquire this nowadays through collectors/reseller. Back when it was released, this was only exclusive at the US Walmart store. You cannot find this in the Philippines.\n\nCondition: Mint',0,1,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(21,1,1,NULL,'Zamac Target Exclusive Porsche 934.5','ZAMAC WALMART EXCLUSIVE PORSCHE 934.5','zamac-target-exclusive-porsche-9345',2025,NULL,0,0,1,0,0,'Mint',1,0,999.00,10,5,'images/products/zamac-target-exclusive-porsche-9345/main.jpg',NULL,'It is a \"Target Store\" Exclusive only Variant of Porsche 934.5. Zamac is one of the most sought after by collectors of hotwheels. It has \"No paint livery, other than its base painting\". You can only acquire this through After market stores or on the USA Target Stores.\n\nCondition: Mint',5,1,'2026-05-14 16:08:04','2026-05-15 08:25:29');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditProductInsert AFTER INSERT ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, new_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_CREATE', CONCAT('Created product: ', NEW.name), 'App\\Models\\Product', NEW.id, 
                JSON_OBJECT('name', NEW.name, 'casting', NEW.casting_name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditProductUpdate AFTER UPDATE ON products FOR EACH ROW
            BEGIN
                IF OLD.stock_quantity <> NEW.stock_quantity OR OLD.price <> NEW.price OR OLD.name <> NEW.name OR OLD.description <> NEW.description THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_UPDATE', CONCAT('Updated product: ', NEW.name), 'App\\Models\\Product', NEW.id, 
                    JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity),
                    JSON_OBJECT('name', NEW.name, 'price', NEW.price, 'stock', NEW.stock_quantity), @current_ip, NOW(), NOW());
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_LowStockAlert AFTER UPDATE ON products FOR EACH ROW
            BEGIN
                IF NEW.stock_quantity <= NEW.low_stock_threshold AND OLD.stock_quantity > NEW.low_stock_threshold THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, created_at, updated_at)
                    VALUES (1, 'LOW_STOCK_ALERT', CONCAT('Low stock alert: ', NEW.name, ' (', NEW.stock_quantity, ' left)'), 'App\\Models\\Product', NEW.id, NOW(), NOW());
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditProductDelete AFTER DELETE ON products FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'PRODUCT_DELETE', CONCAT('Deleted product: ', OLD.name), 'App\\Models\\Product', OLD.id, 
                JSON_OBJECT('name', OLD.name, 'price', OLD.price, 'stock', OLD.stock_quantity), @current_ip, NOW(), NOW());
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,3,5,'Incredible casting! The spectraflame blue is stunning and the golden wheels match the real rally car perfectly.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,1,4,4,'Beautiful car, condition was exactly as described. Dropped one star because I prefer carded, but I knew what I was buying.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(3,2,5,5,'A rare classic! The details on this older Super Treasure Hunt are amazing. Happy to finally add it to my collection.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(4,3,3,5,'That spectraflame pink pops so hard! The Hellcat details are spot on. Fast shipping too!',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(5,3,5,5,'Absolutely massive addition to my Mopar collection. Rubber tires make a huge difference.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(6,4,4,4,'Great premium casting. The Liberty Walk body kit looks very aggressive. Would love to see more colors.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(7,5,3,5,'Been looking for the Forza S15 for months! Arrived in perfect loose condition as described. 10/10.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(8,5,4,5,'The sea blue paint is incredible in person. Metal base gives it nice weight.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(9,5,5,4,'Awesome car, glad I grabbed it before it sold out. Solid JDM piece.',0,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scales`
--

DROP TABLE IF EXISTS `scales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scales`
--

LOCK TABLES `scales` WRITE;
/*!40000 ALTER TABLE `scales` DISABLE KEYS */;
INSERT INTO `scales` VALUES (1,'1:64',0,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `scales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `series` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `series_slug_unique` (`slug`),
  KEY `series_brand_id_foreign` (`brand_id`),
  CONSTRAINT `series_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series`
--

LOCK TABLES `series` WRITE;
/*!40000 ALTER TABLE `series` DISABLE KEYS */;
INSERT INTO `series` VALUES (1,1,'Super Treasure Hunt','super-treasure-hunt',NULL,0,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(2,1,'Premium','premium',NULL,1,'2026-05-14 16:08:04','2026-05-14 16:08:04');
/*!40000 ALTER TABLE `series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0zr020QymTFhKq3telaeFMZOd7h3jppZs0ztPpkY',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVlWemxKT3l3Y1FFT2U4TXprT2lSN25ySGtlT1VSb1JmT0YyOGFWWCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1778804306),('e1DYzHUdI8XBi3lhwFofIc7YyY0VV86rpkcKyyCV',6,'112.201.184.46','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZWFEc3FyMTlLc25mZEtLeHdYNmJZcVM5cjJnajBqMzJ1c1VERTFvYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAvcHJvZHVjdHMvMjEiO3M6NToicm91dGUiO3M6MTM6InByb2R1Y3RzLnNob3ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=',1778862329),('FdTQ39bP3dZmlyG3waVlhOtMaUgE2osJHdz8FBFL',NULL,'2a03:2880:24ff:58::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDNsQnplVmJKV1BCNnVjeGlOY3FLb1htdXIwSUxvM3ZINFRCOHg2aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853286),('FjOi3zKBGdBor7wBHjZnMyBzZrtEcK85efTCI3c4',NULL,'2a03:2880:24ff:43::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMldJeHo3VXIwcHVZd250VGRZdGdMcmQ4VEowUzJlRzJxZFNjME80NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853287),('g4m3SFrW1RTKSjqDPddUaE4j0gQGFOqvSkXAPZ5R',NULL,'2a03:2880:3ff:4f::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUtwZDFWeU8yd3dzTTJnaDFyQnRwNklaang1TFJ1azJ6ZGZEWUJpVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853287),('h236ls3fqSxGQFuVNiFxacRWkiQwQBrAEfLk694K',NULL,'2a03:2880:6ff:72::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoib0R4WEZJSGd0STFjV3VhemQwcXJPSG5ZWXBEYWd6dmhVaWF3MnBlVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTkyOiJodHRwczovLzFhMjAtMTY1LTEwMS0yNTQtMTE4Lm5ncm9rLWZyZWUuYXBwLz9mYmNsaWQ9SXdaWGgwYmdOaFpXMENNVEVBYzNKMFl3WmhjSEJmYVdRTU1qVTJNamd4TURRd05UVTRBQUVlNHhLV3J6YWlTcGgxLTR4Z1E3b2RGZExDSnhvRDlmQVpiWDZUQnM4M1pFVkxWb3NRTllnODE3WFhyOE1fYWVtX2I2ZmQ0MmI0dVVYQl9jQ2h0WU1Hd1EiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853309),('Jg28KTaiYQUXzqHjiIlerwNEKkeS8910n226uaiy',NULL,'2a03:2880:11ff:5d::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0ZGa0o3cjI3bXNReFRHNXVSWUxXVXdmYjhrQ2c2aE1hUkJ3OUJlWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853287),('p6HsCeldGD8UKgRVHCydIAg2iIc7UA9tDkxbctxQ',NULL,'165.101.254.112','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0Z2SGY1QUppdGNjdG03dmdPQkZjSE5OVW5saHduRjlGT3JldG5XcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1778854100),('RB62cOPcVAnIkBquo3GX8gDaAprEE3xMsFIdiJz9',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT21HRWRRYUV5U0hBMTlZRHRVYjZMUTlKcGhjbnNGaTc1YjhRdjJCRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1778853222),('tk6iyxGsyJcY99WgZ3aFHeey265TpewrbyJjnZPc',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoieW1lWTZmYWxGMGhmY1BjZWlZZW5wUHVNN3ZzOG1uMTk0Y000UWYySiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1778804316),('uKt6mpNQe7HQyRM5s31kGZXio6n3qqELX0fHQWEu',NULL,'165.101.254.113','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1RFYTVFRXNaQWxKYXgxM3Vhb09hd0daRFZFZlRJZDlkTHFWT0FRbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHBzOi8vMWEyMC0xNjUtMTAxLTI1NC0xMTgubmdyb2stZnJlZS5hcHAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1778854122),('vl90NVpZd7FluSwnjbhiigqZ0j8fIuFXUZjAj5L0',NULL,'2a03:2880:3ff:50::','facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVRmaGVUS0RkSkE4d3BXclZwblZkS1FSV05QelV6RW1pUlRCdTNwNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTkyOiJodHRwczovLzFhMjAtMTY1LTEwMS0yNTQtMTE4Lm5ncm9rLWZyZWUuYXBwLz9mYmNsaWQ9SXdaWGgwYmdOaFpXMENNVEVBYzNKMFl3WmhjSEJmYVdRTU1qVTJNamd4TURRd05UVTRBQUVleTdDY3RBQ2UyakthaWFWZFlxMHdSNmpyOGFQNHI3MjZBOGJ6UDNFcExIVnZNVFB0cDY5ckFlMkF5ekVfYWVtX01lbUhnVVd2OEFxc0VYeDlmbC12RXciO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1778853317);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `phone` varchar(20) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `default_shipping_address` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `reset_otp` varchar(255) DEFAULT NULL,
  `reset_otp_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'BSMF Admin','admin','admin@bsmfgarage.com','$2y$12$tY0QTvFRdOGeIkhLxztvb.dGkN/o25C3w6zr8NihSDsP3Uzq1xBeq','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-14 16:08:01','2026-05-14 16:08:01'),(2,'Garage Staff','staff','staff@bsmfgarage.com','$2y$12$YUAkQhhlTwU7VjsxdvwSW.l3YyoABhTEDBT5dchVAE9HojOoDlura','staff',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-14 16:08:02','2026-05-14 16:08:02'),(3,'John Doe','johndoe_collector','john@example.com','$2y$12$LlMn8E8cZi83ajEz/QCIHuWjpUPs79bzW5pxmIi57T4ad0fYEdrmu','customer',NULL,NULL,NULL,NULL,'2026-05-14 16:08:03',NULL,NULL,NULL,NULL,'2026-05-14 16:08:03','2026-05-14 16:08:03'),(4,'Jane Smith','jane_diecast','jane@example.com','$2y$12$9ErnFsuh5p9RrH1s0/skHOCFNCL.38yGkGS8mHmcC4mIXQ6J4a37q','customer',NULL,NULL,NULL,NULL,'2026-05-14 16:08:04',NULL,NULL,NULL,NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(5,'Mike Racer','mike_racer99','mike@example.com','$2y$12$x32CfeydDXtuHDDYwBj1ceraH8OZhnmJ4KDiQCmW6wgRG2ajqeXFa','customer',NULL,NULL,NULL,NULL,'2026-05-14 16:08:04',NULL,NULL,NULL,NULL,'2026-05-14 16:08:04','2026-05-14 16:08:04'),(6,'Gyeoul','Gyeoul','yuriasoliven@gmail.com','$2y$12$OQ92bEcGWjUxW1X6IkQomuo6RQwQOjr5.MBAQx6lWan/lQ1VbhMEq','customer',NULL,NULL,NULL,NULL,'2026-05-15 07:59:47',NULL,NULL,NULL,NULL,'2026-05-15 07:57:04','2026-05-15 07:59:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditUserUpdate AFTER UPDATE ON users FOR EACH ROW
            BEGIN
                IF OLD.role <> NEW.role THEN
                    INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, new_values, ip_address, created_at, updated_at)
                    VALUES (COALESCE(@current_user_id, 1), 'USER_ROLE_UPDATE', CONCAT('Updated user ', NEW.username, ' role from ', OLD.role, ' to ', NEW.role), 
                    'App\\Models\\User', NEW.id, JSON_OBJECT('role', OLD.role), JSON_OBJECT('role', NEW.role), @current_ip, NOW(), NOW());
                END IF;
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trig_AuditUserDelete AFTER DELETE ON users FOR EACH ROW
            BEGIN
                INSERT INTO audit_logs (user_id, action, description, model_type, model_id, old_values, ip_address, created_at, updated_at)
                VALUES (COALESCE(@current_user_id, 1), 'USER_DELETE', CONCAT('Deleted user: ', OLD.username), 'App\\Models\\User', OLD.id, 
                JSON_OBJECT('username', OLD.username, 'email', OLD.email), @current_ip, NOW(), NOW());
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping routines for database 'final_project_bsmf'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_CancelOrder` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CancelOrder`(
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
                VALUES (parameter_user_id, 'ORDER_CANCELLED', CONCAT('Cancelled order #', parameter_order_id), 'App\\Models\\Order', parameter_order_id, NOW(), NOW());

                COMMIT;
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_GetAdminDashboardStats` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetAdminDashboardStats`(
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_ProcessOrder` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ProcessOrder`(
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
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_RestockProduct` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_RestockProduct`(
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
                VALUES (parameter_admin_id, 'PRODUCT_RESTOCKED', CONCAT('Restocked ', parameter_quantity, ' units'), 'App\\Models\\Product', parameter_product_id, NOW(), NOW());
                
                COMMIT;
            END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-16  6:44:49
