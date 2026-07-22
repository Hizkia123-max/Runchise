-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: nexapos_db
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounting_coa`
--

DROP TABLE IF EXISTS `accounting_coa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounting_coa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('Asset','Liability','Equity','Revenue','Expense') COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounting_coa`
--

LOCK TABLES `accounting_coa` WRITE;
/*!40000 ALTER TABLE `accounting_coa` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounting_coa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_logs`
--

DROP TABLE IF EXISTS `attendance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `clock_in` timestamp NULL DEFAULT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `gps_latitude_in` decimal(10,8) DEFAULT NULL,
  `gps_longitude_in` decimal(11,8) DEFAULT NULL,
  `gps_latitude_out` decimal(10,8) DEFAULT NULL,
  `gps_longitude_out` decimal(11,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id_employee_id` (`tenant_id`,`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_logs`
--

LOCK TABLES `attendance_logs` WRITE;
/*!40000 ALTER TABLE `attendance_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendance_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `target_table` varchar(60) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `target_id` bigint unsigned DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `geo_radius` int NOT NULL DEFAULT '50',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,1,'Main Branch','Jl. Sudirman No. 1, Jakarta Pusat','021-12345678',-6.20880000,106.84560000,50,'2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,1,'Laptops & PCs','2026-07-06 11:16:50','2026-07-06 11:16:50'),(2,1,'Components','2026-07-06 11:16:50','2026-07-06 11:16:50'),(3,1,'Peripherals','2026-07-06 11:16:50','2026-07-06 11:16:50'),(4,1,'Networking','2026-07-06 11:16:50','2026-07-06 11:16:50'),(5,1,'Services','2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `membership_tier` enum('Bronze','Silver','Gold','Platinum') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Bronze',
  `loyalty_points` int NOT NULL DEFAULT '0',
  `total_spent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `identity_card` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hired_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods_receiving_items`
--

DROP TABLE IF EXISTS `goods_receiving_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_receiving_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `goods_receiving_id` bigint unsigned NOT NULL,
  `purchase_order_item_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity_received` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_receiving_id` (`goods_receiving_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_receiving_items`
--

LOCK TABLES `goods_receiving_items` WRITE;
/*!40000 ALTER TABLE `goods_receiving_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `goods_receiving_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods_receivings`
--

DROP TABLE IF EXISTS `goods_receivings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_receivings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `purchase_order_id` bigint unsigned NOT NULL,
  `gr_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `received_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `received_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_id_gr_number` (`tenant_id`,`gr_number`),
  KEY `purchase_order_id` (`purchase_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_receivings`
--

LOCK TABLES `goods_receivings` WRITE;
/*!40000 ALTER TABLE `goods_receivings` DISABLE KEYS */;
/*!40000 ALTER TABLE `goods_receivings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_stocks`
--

DROP TABLE IF EXISTS `inventory_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id_branch_id` (`tenant_id`,`branch_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_stocks`
--

LOCK TABLES `inventory_stocks` WRITE;
/*!40000 ALTER TABLE `inventory_stocks` DISABLE KEYS */;
INSERT INTO `inventory_stocks` VALUES (1,1,1,1,76.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(2,1,1,2,54.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(3,1,1,3,24.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(4,1,1,4,19.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(5,1,1,5,14.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(6,1,1,6,10.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(7,1,1,7,7.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(8,1,1,8,85.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(9,1,1,9,41.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(10,1,1,10,89.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(11,1,1,11,79.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(12,1,1,12,8.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(13,1,1,13,8.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(14,1,1,14,88.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(15,1,1,15,74.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(16,1,1,16,40.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(17,1,1,17,15.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(18,1,1,18,15.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(19,1,1,19,87.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(20,1,1,20,7.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(21,1,1,21,15.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(22,1,1,22,80.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(23,1,1,23,43.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(24,1,1,24,42.00,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(25,1,1,25,6.00,'2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `inventory_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_entries`
--

DROP TABLE IF EXISTS `journal_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `reference_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `posting_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_entries`
--

LOCK TABLES `journal_entries` WRITE;
/*!40000 ALTER TABLE `journal_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journal_lines`
--

DROP TABLE IF EXISTS `journal_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `journal_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `journal_entry_id` bigint unsigned NOT NULL,
  `coa_id` bigint unsigned NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `journal_entry_id` (`journal_entry_id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journal_lines`
--

LOCK TABLES `journal_lines` WRITE;
/*!40000 ALTER TABLE `journal_lines` DISABLE KEYS */;
/*!40000 ALTER TABLE `journal_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (37,'2026-06-01-000000','App\\Database\\Migrations\\CreateSessionsTable','default','App',1783336609,1),(38,'2026-06-01-000001','App\\Database\\Migrations\\CreateTenantsTable','default','App',1783336609,1),(39,'2026-06-01-000002','App\\Database\\Migrations\\CreateUsersTable','default','App',1783336609,1),(40,'2026-06-01-000003','App\\Database\\Migrations\\CreateBranchesAndWarehousesTable','default','App',1783336609,1),(41,'2026-06-01-000004','App\\Database\\Migrations\\CreatePOSTransactionsTables','default','App',1783336609,1),(42,'2026-06-01-000005','App\\Database\\Migrations\\CreateAccountingTables','default','App',1783336610,1),(43,'2026-06-01-000006','App\\Database\\Migrations\\CreateSaaSBillingTables','default','App',1783336610,1),(44,'2026-06-01-000007','App\\Database\\Migrations\\CreateCategoriesTable','default','App',1783336610,1),(45,'2026-06-01-000008','App\\Database\\Migrations\\CreateWastedProductsTable','default','App',1783336610,1),(46,'2026-06-01-000009','App\\Database\\Migrations\\CreateTransactionReturnsTables','default','App',1783336610,1),(47,'2026-06-01-000010','App\\Database\\Migrations\\CreatePurchasingTables','default','App',1783336610,1),(48,'2026-07-01-000000','App\\Database\\Migrations\\CreatePurchaseReturnsTables','default','App',1783336610,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_sessions`
--

DROP TABLE IF EXISTS `pos_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `opening_cash` decimal(15,2) NOT NULL DEFAULT '0.00',
  `closing_cash` decimal(15,2) DEFAULT NULL,
  `status` enum('Open','Closed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Open',
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id_branch_id` (`tenant_id`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_sessions`
--

LOCK TABLES `pos_sessions` WRITE;
/*!40000 ALTER TABLE `pos_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `pos_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reorder_point` int NOT NULL DEFAULT '10',
  `category_id` bigint unsigned DEFAULT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_id_sku` (`tenant_id`,`sku`),
  KEY `tenant_id` (`tenant_id`),
  KEY `barcode` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'PC-001','Lenovo ThinkPad X1 Carbon',NULL,25000000.00,20000000.00,2,1,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(2,1,'PC-002','Asus ROG Zephyrus G14',NULL,30000000.00,25000000.00,2,1,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(3,1,'PC-003','Dell XPS 15',NULL,28000000.00,23000000.00,1,1,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(4,1,'PC-004','MacBook Air M2',NULL,18000000.00,15000000.00,3,1,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(5,1,'PC-005','Custom Build PC Intel i7',NULL,15000000.00,12000000.00,5,1,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(6,1,'CP-001','NVIDIA RTX 4070 Ti',NULL,14000000.00,12000000.00,2,2,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(7,1,'CP-002','AMD Ryzen 7 7800X3D',NULL,7000000.00,6000000.00,5,2,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(8,1,'CP-003','Corsair Vengeance 32GB DDR5',NULL,2500000.00,2000000.00,10,2,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(9,1,'CP-004','Samsung 990 PRO 2TB NVMe',NULL,3500000.00,2800000.00,8,2,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(10,1,'CP-005','Seasonic Focus 850W Gold',NULL,2200000.00,1800000.00,5,2,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(11,1,'PR-001','Logitech G Pro X Superlight',NULL,2000000.00,1500000.00,5,3,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(12,1,'PR-002','Keychron Q1 Pro Mechanical',NULL,3500000.00,2500000.00,3,3,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(13,1,'PR-003','LG UltraGear 27\" 1440p 165Hz',NULL,6000000.00,5000000.00,2,3,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(14,1,'PR-004','HyperX Cloud III Gaming Headset',NULL,1500000.00,1100000.00,5,3,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(15,1,'PR-005','Elgato Stream Deck MK.2',NULL,2800000.00,2200000.00,3,3,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(16,1,'NW-001','Asus RT-AX88U Router Wi-Fi 6',NULL,4500000.00,3500000.00,3,4,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(17,1,'NW-002','TP-Link Deco X20 Mesh 3-Pack',NULL,3200000.00,2500000.00,4,4,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(18,1,'NW-003','Ubiquiti UniFi AP AC Pro',NULL,2500000.00,2000000.00,5,4,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(19,1,'NW-004','Kabel LAN Cat6 Belden 305m',NULL,2000000.00,1500000.00,2,4,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(20,1,'NW-005','Switch Hub Gigabit 16-Port',NULL,800000.00,600000.00,5,4,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(21,1,'SV-001','Instalasi Windows & Office',NULL,150000.00,0.00,0,5,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(22,1,'SV-002','Jasa Perakitan PC',NULL,300000.00,0.00,0,5,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(23,1,'SV-003','Cleaning & Ganti Thermal Paste',NULL,200000.00,20000.00,0,5,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(24,1,'SV-004','Data Recovery Ringan',NULL,500000.00,0.00,0,5,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(25,1,'SV-005','Pemasangan Jaringan LAN (titik)',NULL,100000.00,20000.00,0,5,NULL,NULL,'2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `purchase_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity_ordered` decimal(10,2) NOT NULL,
  `quantity_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_id` (`purchase_order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
INSERT INTO `purchase_order_items` VALUES (1,1,1,1,2.00,2.00,20000000.00,40000000.00,'2026-07-01 11:16:50','2026-07-01 11:16:50'),(2,1,1,12,2.00,2.00,2500000.00,5000000.00,'2026-07-01 11:16:50','2026-07-01 11:16:50');
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `po_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `status` enum('Draft','Ordered','Partially Received','Completed','Cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Draft',
  `notes` text COLLATE utf8mb4_general_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_id_po_number` (`tenant_id`,`po_number`),
  KEY `supplier_id` (`supplier_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (1,1,1,1,'PO-00001','2026-07-01','2026-07-04','Completed','Pembelian stok awal bulan',45000000.00,1,'2026-07-01 11:16:50','2026-07-01 11:16:50');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_return_items`
--

DROP TABLE IF EXISTS `purchase_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_return_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `purchase_return_id` bigint unsigned NOT NULL,
  `purchase_order_item_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `refund_amount` decimal(15,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deducted_from_stock` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_return_id` (`purchase_return_id`),
  KEY `purchase_order_item_id` (`purchase_order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_items`
--

LOCK TABLES `purchase_return_items` WRITE;
/*!40000 ALTER TABLE `purchase_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_returns`
--

DROP TABLE IF EXISTS `purchase_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `purchase_order_id` bigint unsigned NOT NULL,
  `return_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `total_refunded` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_general_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_id_return_number` (`tenant_id`,`return_number`),
  KEY `purchase_order_id` (`purchase_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_returns`
--

LOCK TABLES `purchase_returns` WRITE;
/*!40000 ALTER TABLE `purchase_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saas_plans`
--

DROP TABLE IF EXISTS `saas_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price_monthly` decimal(15,2) NOT NULL,
  `max_branches` int NOT NULL DEFAULT '1',
  `max_users` int NOT NULL DEFAULT '3',
  `max_monthly_transactions` int NOT NULL DEFAULT '1000',
  `has_accounting` tinyint(1) NOT NULL DEFAULT '0',
  `has_hr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_plans`
--

LOCK TABLES `saas_plans` WRITE;
/*!40000 ALTER TABLE `saas_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `saas_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saas_subscriptions`
--

DROP TABLE IF EXISTS `saas_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `plan_id` bigint unsigned NOT NULL,
  `status` enum('Trial','Active','GracePeriod','Suspended') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Trial',
  `starts_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_subscriptions`
--

LOCK TABLES `saas_subscriptions` WRITE;
/*!40000 ALTER TABLE `saas_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `saas_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_card_entries`
--

DROP TABLE IF EXISTS `stock_card_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_card_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `entry_date` datetime NOT NULL,
  `type` enum('IN','OUT') COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id_branch_id_product_id` (`tenant_id`,`branch_id`,`product_id`),
  KEY `entry_date` (`entry_date`),
  KEY `reference_type_reference_id` (`reference_type`,`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_card_entries`
--

LOCK TABLES `stock_card_entries` WRITE;
/*!40000 ALTER TABLE `stock_card_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_card_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,1,'PT. Indokomputer Jaya','Bapak Budi','081234567890','budi@indokomputer.com','Mangga Dua Mall Lt. 3, Jakarta','2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `subdomain` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `custom_domain` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Pending','Active','Suspended') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain` (`subdomain`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Runchise Demo Store','demo',NULL,'Active','2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_items`
--

DROP TABLE IF EXISTS `transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `transaction_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `tenant_id` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_items`
--

LOCK TABLES `transaction_items` WRITE;
/*!40000 ALTER TABLE `transaction_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_return_items`
--

DROP TABLE IF EXISTS `transaction_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_return_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `return_id` bigint unsigned NOT NULL,
  `transaction_item_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `refund_amount` decimal(15,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `returned_to_stock` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `return_id` (`return_id`),
  KEY `transaction_item_id` (`transaction_item_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_return_items`
--

LOCK TABLES `transaction_return_items` WRITE;
/*!40000 ALTER TABLE `transaction_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_returns`
--

DROP TABLE IF EXISTS `transaction_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `transaction_id` bigint unsigned NOT NULL,
  `total_refunded` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_returns`
--

LOCK TABLES `transaction_returns` WRITE;
/*!40000 ALTER TABLE `transaction_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `pos_session_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('Cash','QRIS','Card','Split') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Cash',
  `payment_status` enum('Unpaid','Paid','Refunded') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `tenant_id_branch_id` (`tenant_id`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('SuperAdmin','TenantOwner','Manager','Cashier','InventoryStaff','Accountant','HRStaff') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Cashier',
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mfa_secret` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mfa_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Owner Runchise','owner@runchise.com','$2y$10$hEeuCCBsJC2TbgNuCI3fReM03Uz8HXUKya53nj9hF/KddKWwGeezG','TenantOwner',NULL,NULL,0,'2026-07-06 11:16:50','2026-07-06 11:16:50'),(2,1,'Manager Runchise','admin@runchise.com','$2y$10$nLJiBizrc3M2jRva9/Mi.ewz9op58v4To3xOTlVqSwPLYvocyPDjO','Manager',NULL,NULL,0,'2026-07-06 11:16:50','2026-07-06 11:16:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wasted_products`
--

DROP TABLE IF EXISTS `wasted_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wasted_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wasted_products`
--

LOCK TABLES `wasted_products` WRITE;
/*!40000 ALTER TABLE `wasted_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `wasted_products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-07  5:40:40
