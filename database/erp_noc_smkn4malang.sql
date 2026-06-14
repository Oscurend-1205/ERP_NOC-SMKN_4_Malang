-- ============================================================
-- ERP NOC - SMKN 4 Malang
-- Unified Database Schema (Structure Only — No Seed Data)
-- Generated: 2026-06-11
-- Database: erp_noc_smkn4malang
-- ============================================================
-- This is the SINGLE source of truth for the database schema.
-- All tables used by the Laravel application are included here.
-- All latest migration changes are incorporated.
-- NO INSERT data (fresh/empty tables on deploy).
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `erp_noc_smkn4malang`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `erp_noc_smkn4malang`;

-- ============================================================
-- 1. LARAVEL SYSTEM TABLES
-- ============================================================

-- -----------------------------------------------------------
-- Table: users (Auth & User Management)
-- Model: App\Models\User
-- Migrations: 0001_01_01_000000, 2026_04_23_181050,
--             2026_05_21_234440 (user_code), 2026_06_07_232903 (drop email unique)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_user_code_unique` (`user_code`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: sessions (Laravel Session)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: password_reset_tokens
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: cache
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: cache_locks
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: jobs
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` bigint UNSIGNED NOT NULL,
  `reserved_at` bigint UNSIGNED DEFAULT NULL,
  `available_at` bigint UNSIGNED NOT NULL,
  `created_at` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: job_batches
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` bigint UNSIGNED NOT NULL,
  `pending_jobs` bigint UNSIGNED NOT NULL,
  `failed_jobs` bigint UNSIGNED NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` bigint UNSIGNED DEFAULT NULL,
  `created_at` bigint UNSIGNED NOT NULL,
  `finished_at` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: failed_jobs
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: migrations
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migration records so Laravel knows which migrations have been applied
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_23_000001_create_categories_table', 1),
(5, '2026_04_23_000002_create_locations_table', 1),
(6, '2026_04_23_000003_create_items_table', 1),
(7, '2026_04_23_000004_create_item_movements_table', 1),
(8, '2026_04_23_181050_add_details_to_users_table', 2),
(9, '2026_04_25_000001_add_superadmin_user', 3),
(10, '2026_04_25_200001_create_scan_sessions_table', 3),
(11, '2026_04_25_200002_create_peminjaman_table', 3),
(12, '2026_05_21_234440_modify_primary_keys_for_deployment', 4),
(13, '2026_06_03_191113_create_jurusans_table', 5),
(14, '2026_06_03_191837_create_suppliers_table', 5),
(15, '2026_06_03_191842_create_kondisi_barangs_table', 5),
(16, '2026_06_03_191848_create_asal_barangs_table', 5),
(17, '2026_06_07_232903_drop_email_unique_from_users_table', 6),
(18, '2026_06_08_172433_add_penanggung_jawab_to_locations_table', 7),
(19, '2026_06_10_000001_add_prefix_and_last_code_number_to_categories_table', 8),
(20, '2026_06_10_000002_add_missing_columns_to_jurusans_table', 8),
(21, '2026_06_12_000001_add_sub_prefix_to_items_table', 9),
(22, '2026_06_12_000002_add_fk_columns_to_items_and_kondisi_kembali_to_peminjaman', 9),
(23, '2026_06_12_000003_add_keterangan_and_foto_kembali_to_peminjaman', 9);

-- ============================================================
-- DEFAULT USER ACCOUNTS (Admin & Superadmin)
-- Passwords match password.txt credentials
-- ============================================================
INSERT INTO `users` (`user_code`, `name`, `username`, `email`, `role`, `password`, `is_active`, `created_at`, `updated_at`) VALUES
('USR-001', 'Super Admin NOC', 'superadmin', 'superadmin@noc.smkn4malang.sch.id', 'Superadmin', '$2y$10$Fw7wR0Fdj6ex0xPAnpbEm.k5gjZJDUPg3op9uidWzgYp6Lo81JeMO', 1, NOW(), NOW()),
('USR-002', 'Admin NOC', 'admin', 'admin@noc.smkn4malang.sch.id', 'Admin', '$2y$10$HjjtLnVuGcz7o.x/06jZEe0UhxmsyKvHyYNvb9IciZl0A2sTWT6Mu', 1, NOW(), NOW());

-- ============================================================
-- 2. APPLICATION TABLES (Inventaris & Manajemen Barang)
-- ============================================================

-- -----------------------------------------------------------
-- Table: categories (Kategori Barang)
-- Model: App\Models\Category
-- Migrations: 2026_04_23_000001, 2026_06_10_000001 (prefix, last_code_number)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_code_number` int UNSIGNED NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  UNIQUE KEY `categories_prefix_unique` (`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: locations (Lokasi / Ruangan)
-- Model: App\Models\Location
-- Migrations: 2026_04_23_000002, 2026_06_08_172433 (penanggung_jawab)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penanggung_jawab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: items (Inventaris Barang)
-- Model: App\Models\Item
-- Migration: 2026_04_23_000003, 2026_05_21_234440 (code varchar 50),
--            2026_06_12_000001 (sub_prefix), 2026_06_12_000002 (fk columns)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `location_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `asal_barang_id` bigint UNSIGNED DEFAULT NULL,
  `kondisi_barang_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` bigint UNSIGNED NOT NULL DEFAULT '1',
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_code_unique` (`code`),
  KEY `fk_items_category_id` (`category_id`),
  KEY `fk_items_location_id` (`location_id`),
  KEY `fk_items_supplier_id` (`supplier_id`),
  KEY `fk_items_asal_barang_id` (`asal_barang_id`),
  KEY `fk_items_kondisi_barang_id` (`kondisi_barang_id`),
  CONSTRAINT `fk_items_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_items_asal_barang_id` FOREIGN KEY (`asal_barang_id`) REFERENCES `asal_barangs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_items_kondisi_barang_id` FOREIGN KEY (`kondisi_barang_id`) REFERENCES `kondisi_barangs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: item_movements (Riwayat Pergerakan Barang)
-- Model: App\Models\ItemMovement
-- Migration: 2026_04_23_000004
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `item_movements`;
CREATE TABLE `item_movements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_location_id` bigint UNSIGNED DEFAULT NULL,
  `to_location_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` bigint UNSIGNED NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `movement_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_movements_item_id` (`item_id`),
  KEY `fk_item_movements_user_id` (`user_id`),
  KEY `fk_item_movements_from_location_id` (`from_location_id`),
  KEY `fk_item_movements_to_location_id` (`to_location_id`),
  CONSTRAINT `fk_item_movements_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_movements_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_movements_from_location_id` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_item_movements_to_location_id` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: scan_sessions (Sesi QR Scan)
-- Model: App\Models\ScanSession
-- Migration: 2026_04_25_200001
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `scan_sessions`;
CREATE TABLE `scan_sessions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scan_sessions_token_unique` (`token`),
  KEY `scan_sessions_token_expired_at_index` (`token`, `expired_at`),
  CONSTRAINT `scan_sessions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: peminjaman (Data Peminjaman Barang via QR)
-- Model: App\Models\Peminjaman
-- Migrations: 2026_04_25_200002, 2026_05_21_234440 (id -> id_pinjam),
--             2026_06_12_000002 (kondisi_saat_kembali)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE `peminjaman` (
  `id_pinjam` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_peminjam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_pinjam` timestamp NULL DEFAULT NULL,
  `waktu_kembali` timestamp NULL DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `kondisi_saat_kembali` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan_kembali` text COLLATE utf8mb4_unicode_ci,
  `foto_kembali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pinjam`),
  KEY `peminjaman_session_token_index` (`session_token`),
  KEY `peminjaman_status_index` (`status`),
  CONSTRAINT `peminjaman_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: jurusans (Data Jurusan)
-- Model: App\Models\Jurusan
-- Migrations: 2026_06_03_191113, 2026_06_10_000002
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `jurusans`;
CREATE TABLE `jurusans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: suppliers (Data Supplier)
-- Model: App\Models\Supplier
-- Migration: 2026_06_03_191837
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: asal_barangs (Data Asal Barang)
-- Model: App\Models\AsalBarang
-- Migration: 2026_06_03_191848
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `asal_barangs`;
CREATE TABLE `asal_barangs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: kondisi_barangs (Data Kondisi Barang)
-- Model: App\Models\KondisiBarang
-- Migration: 2026_06_03_191842
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `kondisi_barangs`;
CREATE TABLE `kondisi_barangs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DONE
-- ============================================================

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
