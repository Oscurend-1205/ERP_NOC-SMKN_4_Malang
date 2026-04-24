-- ============================================
-- ERP NOC - SMKN 4 Malang
-- MySQL Database Export
-- Generated: 2026-04-24 07:06:32
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `erp_noc_smkn4malang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp_noc_smkn4malang`;

-- -------------------------------------------
-- Table: `cache`
-- -------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR,
  `value` TEXT NOT NULL,
  `expiration` BIGINT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `cache_locks`
-- -------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR,
  `owner` VARCHAR NOT NULL,
  `expiration` BIGINT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `categories`
-- -------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR NOT NULL,
  `slug` VARCHAR NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `categories`
LOCK TABLES `categories` WRITE;
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Router', 'router', 'Perangkat router untuk jaringan', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Switch', 'switch', 'Perangkat switch jaringan', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'Access Point', 'access-point', 'Perangkat wireless access point', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Kabel & Konektor', 'kabel-konektor', 'Kabel UTP, STP, Fiber Optic, dan konektor', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Server', 'server', 'Perangkat server dan rack', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'Komputer', 'komputer', 'PC Desktop dan workstation', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(7, 'Monitor', 'monitor', 'Monitor dan display', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(8, 'Tools & Alat Ukur', 'tools-alat-ukur', 'Tang crimping, LAN tester, multimeter, dll', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(9, 'Peripheral', 'peripheral', 'Keyboard, mouse, headset, dll', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(10, 'Lainnya', 'lainnya', 'Perangkat lain yang tidak masuk kategori di atas', '2026-04-23 14:56:44', '2026-04-23 14:56:44');
UNLOCK TABLES;

-- -------------------------------------------
-- Table: `failed_jobs`
-- -------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `uuid` VARCHAR NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` TEXT NOT NULL,
  `exception` TEXT NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `item_movements`
-- -------------------------------------------
DROP TABLE IF EXISTS `item_movements`;
CREATE TABLE `item_movements` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `item_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `type` VARCHAR NOT NULL,
  `from_location_id` BIGINT DEFAULT NULL,
  `to_location_id` BIGINT DEFAULT NULL,
  `quantity` BIGINT NOT NULL DEFAULT 1,
  `notes` TEXT DEFAULT NULL,
  `movement_date` DATE NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_item_movements_to_location_id` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_item_movements_from_location_id` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_item_movements_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_movements_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `items`
-- -------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR NOT NULL,
  `code` VARCHAR NOT NULL,
  `serial_number` VARCHAR DEFAULT NULL,
  `brand` VARCHAR DEFAULT NULL,
  `model` VARCHAR DEFAULT NULL,
  `category_id` BIGINT NOT NULL,
  `location_id` BIGINT NOT NULL,
  `quantity` BIGINT NOT NULL DEFAULT 1,
  `condition` VARCHAR NOT NULL DEFAULT 'baik',
  `status` VARCHAR NOT NULL DEFAULT 'tersedia',
  `purchase_date` DATE DEFAULT NULL,
  `purchase_price` DECIMAL(15,2) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `image` VARCHAR DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_code_unique` (`code`),
  CONSTRAINT `fk_items_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `items`
LOCK TABLES `items` WRITE;
INSERT INTO `items` (`id`, `name`, `code`, `serial_number`, `brand`, `model`, `category_id`, `location_id`, `quantity`, `condition`, `status`, `purchase_date`, `purchase_price`, `notes`, `image`, `created_at`, `updated_at`) VALUES
(1, 'MikroTik RB750Gr3', 'INV-00001', NULL, 'MikroTik', 'RB750Gr3', 1, 1, 5, 'baik', 'tersedia', NULL, 850000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Cisco Catalyst 2960', 'INV-00002', NULL, 'Cisco', 'Catalyst 2960-24TT', 2, 1, 3, 'baik', 'tersedia', NULL, 5500000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'TP-Link EAP245', 'INV-00003', NULL, 'TP-Link', 'EAP245 V3', 3, 2, 4, 'baik', 'tersedia', NULL, 1200000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Kabel UTP Cat6 305m', 'INV-00004', NULL, 'Belden', 'Cat6 UTP', 4, 5, 10, 'baik', 'tersedia', NULL, 1800000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Dell PowerEdge T340', 'INV-00005', NULL, 'Dell', 'PowerEdge T340', 5, 4, 1, 'baik', 'tersedia', NULL, 25000000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'PC Desktop Lenovo V530', 'INV-00006', NULL, 'Lenovo', 'V530 Tower', 6, 3, 20, 'baik', 'tersedia', NULL, 8500000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(7, 'Monitor LG 22MK430H', 'INV-00007', NULL, 'LG', '22MK430H', 7, 3, 20, 'baik', 'tersedia', NULL, 1500000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(8, 'Tang Crimping RJ45', 'INV-00008', NULL, 'AMP', 'Crimping Tool', 8, 1, 15, 'rusak_ringan', 'tersedia', NULL, 150000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(9, 'MikroTik CCR1009', 'INV-00009', NULL, 'MikroTik', 'CCR1009-7G-1C-1S+', 1, 4, 1, 'baik', 'tersedia', NULL, 7500000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(10, 'Cisco Router 2901', 'INV-00010', NULL, 'Cisco', '2901/K9', 1, 2, 2, 'rusak_berat', 'maintenance', NULL, 12000000, NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44');
UNLOCK TABLES;

-- -------------------------------------------
-- Table: `job_batches`
-- -------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR,
  `name` VARCHAR NOT NULL,
  `total_jobs` BIGINT NOT NULL,
  `pending_jobs` BIGINT NOT NULL,
  `failed_jobs` BIGINT NOT NULL,
  `failed_job_ids` TEXT NOT NULL,
  `options` TEXT DEFAULT NULL,
  `cancelled_at` BIGINT DEFAULT NULL,
  `created_at` BIGINT NOT NULL,
  `finished_at` BIGINT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `jobs`
-- -------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `queue` VARCHAR NOT NULL,
  `payload` TEXT NOT NULL,
  `attempts` BIGINT NOT NULL,
  `reserved_at` BIGINT DEFAULT NULL,
  `available_at` BIGINT NOT NULL,
  `created_at` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `locations`
-- -------------------------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR NOT NULL,
  `code` VARCHAR NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `locations`
LOCK TABLES `locations` WRITE;
INSERT INTO `locations` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Lab Jaringan 1', 'LAB-JR1', 'Laboratorium Jaringan Dasar', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Lab Jaringan 2', 'LAB-JR2', 'Laboratorium Jaringan Lanjut', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'Lab Komputer 1', 'LAB-KP1', 'Laboratorium Komputer Umum', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Lab Server', 'LAB-SVR', 'Ruang Server NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Gudang NOC', 'GDG-NOC', 'Gudang penyimpanan peralatan NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'Ruang Instruktur', 'RNG-INS', 'Ruang kerja instruktur', '2026-04-23 14:56:44', '2026-04-23 14:56:44');
UNLOCK TABLES;

-- -------------------------------------------
-- Table: `migrations`
-- -------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `migration` VARCHAR NOT NULL,
  `batch` BIGINT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `migrations`
LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_23_000001_create_categories_table', 1),
(5, '2026_04_23_000002_create_locations_table', 1),
(6, '2026_04_23_000003_create_items_table', 1),
(7, '2026_04_23_000004_create_item_movements_table', 1),
(8, '2026_04_23_181050_add_details_to_users_table', 2);
UNLOCK TABLES;

-- -------------------------------------------
-- Table: `password_reset_tokens`
-- -------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR,
  `token` VARCHAR NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: `sessions`
-- -------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR,
  `user_id` BIGINT DEFAULT NULL,
  `ip_address` VARCHAR DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` TEXT NOT NULL,
  `last_activity` BIGINT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `sessions`
LOCK TABLES `sessions` WRITE;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('t7nt7uVU36XgB6cncJY4ikNO6H6zXIBOXFeXHZLz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJtSXlmVWlETGxwMHJpQ1BQUlh0aWR5aURmNmFVQnZDVXlqS1hucVYwIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1777012819);
UNLOCK TABLES;

-- -------------------------------------------
-- Table: `users`
-- -------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR NOT NULL,
  `email` VARCHAR NOT NULL,
  `email_verified_at` DATETIME DEFAULT NULL,
  `password` VARCHAR NOT NULL,
  `remember_token` VARCHAR DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `username` VARCHAR DEFAULT NULL,
  `role` VARCHAR NOT NULL DEFAULT 'Admin',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `role`, `is_active`) VALUES
(1, 'Admin NOC', 'admin@noc.smkn4malang.sch.id', '2026-04-23 14:56:43', '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', 'mxPUu6cSD3ZQfN1GrenJzNvThFEi8XIuK8f34yzzZsxQ8iaKRgN41rCoYSAJ', '2026-04-23 14:56:44', '2026-04-23 15:31:57', NULL, 'Admin', 1),
(2, 'Andika Galon Lima Liter', 'andika@gmail.com', NULL, '$2y$12$/.NBAODTvaRxqsPJ.ruEYeHMc5pWmyILBG17ar8asrHz/zZZUecMC', NULL, '2026-04-23 18:21:29', '2026-04-23 18:21:29', 'Adidika', 'User', 1),
(3, 'Bagus Ganteng', 'bagus@example.com', NULL, '$2y$12$ewMkrwuzmWhRyGFNWtixB.SBxfmXW0JNHQlKAhEUOZ0mhiiTGdGk.', NULL, '2026-04-24 06:38:24', '2026-04-24 06:38:24', 'gusgus', 'User', 1);
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS = 1;
-- ============================================
-- Export completed successfully
-- ============================================
