-- ============================================================
-- ERP NOC - SMKN 4 Malang
-- Unified Database Schema
-- Generated: 2026-05-21
-- Database: erp_noc_smkn4malang
-- ============================================================
-- This is the SINGLE source of truth for the database schema.
-- All tables used by the Laravel application are included here.
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
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `role`, `is_active`) VALUES
(1, 'Admin NOC', 'admin@noc.smkn4malang.sch.id', '2026-04-23 14:56:43', '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', 'mxPUu6cSD3ZQfN1GrenJzNvThFEi8XIuK8f34yzzZsxQ8iaKRgN41rCoYSAJ', '2026-04-23 14:56:44', '2026-04-23 15:31:57', NULL, 'Admin', 1),
(2, 'Andika Galon Lima Liter', 'andika@gmail.com', NULL, '$2y$12$/.NBAODTvaRxqsPJ.ruEYeHMc5pWmyILBG17ar8asrHz/zZZUecMC', NULL, '2026-04-23 18:21:29', '2026-04-23 18:21:29', 'Adidika', 'User', 1),
(3, 'Bagus Ganteng', 'bagus@example.com', NULL, '$2y$12$ewMkrwuzmWhRyGFNWtixB.SBxfmXW0JNHQlKAhEUOZ0mhiiTGdGk.', NULL, '2026-04-24 06:38:24', '2026-04-24 06:38:24', 'gusgus', 'User', 1),
(4, 'Superadmin NOC', 'superadmin@noc.smkn4malang.sch.id', NULL, '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', NULL, '2026-04-25 18:48:00', '2026-04-25 18:48:00', 'superadmin', 'Superadmin', 1);

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
(11, '2026_04_25_200002_create_peminjaman_table', 3);

-- ============================================================
-- 2. APPLICATION TABLES (Inventaris & Manajemen Barang)
-- ============================================================

-- -----------------------------------------------------------
-- Table: categories (Kategori Barang)
-- Model: App\Models\Category
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- -----------------------------------------------------------
-- Table: locations (Lokasi / Ruangan)
-- Model: App\Models\Location
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locations_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `locations` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Lab Jaringan 1', 'LAB-JR1', 'Laboratorium Jaringan Dasar', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Lab Jaringan 2', 'LAB-JR2', 'Laboratorium Jaringan Lanjut', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'Lab Komputer 1', 'LAB-KP1', 'Laboratorium Komputer Umum', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Lab Server', 'LAB-SVR', 'Ruang Server NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Gudang NOC', 'GDG-NOC', 'Gudang penyimpanan peralatan NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'Ruang Instruktur', 'RNG-INS', 'Ruang kerja instruktur', '2026-04-23 14:56:44', '2026-04-23 14:56:44');

-- -----------------------------------------------------------
-- Table: items (Inventaris Barang)
-- Model: App\Models\Item
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `location_id` bigint UNSIGNED NOT NULL,
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
  CONSTRAINT `fk_items_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `items` (`id`, `name`, `code`, `serial_number`, `brand`, `model`, `category_id`, `location_id`, `quantity`, `condition`, `status`, `purchase_date`, `purchase_price`, `notes`, `image`, `created_at`, `updated_at`) VALUES
(1, 'MikroTik RB750Gr3', 'INV-00001', NULL, 'MikroTik', 'RB750Gr3', 1, 1, 5, 'baik', 'tersedia', NULL, '850000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Cisco Catalyst 2960', 'INV-00002', NULL, 'Cisco', 'Catalyst 2960-24TT', 2, 1, 3, 'baik', 'tersedia', NULL, '5500000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'TP-Link EAP245', 'INV-00003', NULL, 'TP-Link', 'EAP245 V3', 3, 2, 4, 'baik', 'tersedia', NULL, '1200000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Kabel UTP Cat6 305m', 'INV-00004', NULL, 'Belden', 'Cat6 UTP', 4, 5, 10, 'baik', 'tersedia', NULL, '1800000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Dell PowerEdge T340', 'INV-00005', NULL, 'Dell', 'PowerEdge T340', 5, 4, 1, 'baik', 'tersedia', NULL, '25000000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'PC Desktop Lenovo V530', 'INV-00006', NULL, 'Lenovo', 'V530 Tower', 6, 3, 20, 'baik', 'tersedia', NULL, '8500000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(7, 'Monitor LG 22MK430H', 'INV-00007', NULL, 'LG', '22MK430H', 7, 3, 20, 'baik', 'tersedia', NULL, '1500000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(8, 'Tang Crimping RJ45', 'INV-00008', NULL, 'AMP', 'Crimping Tool', 8, 1, 15, 'rusak_ringan', 'tersedia', NULL, '150000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(9, 'MikroTik CCR1009', 'INV-00009', NULL, 'MikroTik', 'CCR1009-7G-1C-1S+', 1, 4, 1, 'baik', 'tersedia', NULL, '7500000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(10, 'Cisco Router 2901', 'INV-00010', NULL, 'Cisco', '2901/K9', 1, 2, 2, 'rusak_berat', 'maintenance', NULL, '12000000.00', NULL, NULL, '2026-04-23 14:56:44', '2026-04-23 14:56:44');

-- -----------------------------------------------------------
-- Table: item_movements (Riwayat Pergerakan Barang)
-- Model: App\Models\ItemMovement
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
-- Migration: 2026_04_25_200001_create_scan_sessions_table
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
-- Migration: 2026_04_25_200002_create_peminjaman_table
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE `peminjaman` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_peminjam` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_pinjam` timestamp NULL DEFAULT NULL,
  `waktu_kembali` timestamp NULL DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_session_token_index` (`session_token`),
  KEY `peminjaman_status_index` (`status`),
  CONSTRAINT `peminjaman_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: jurusans (Data Jurusan)
-- Model: App\Models\Jurusan
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
