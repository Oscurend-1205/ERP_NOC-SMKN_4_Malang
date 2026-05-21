-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2026 at 05:45 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_noc_smkn4malang`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `ruangan_id` bigint UNSIGNED NOT NULL,
  `nama_barang` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kondisi` enum('baik','kurang_baik','rusak_berat','dalam_perbaikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baik',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kategori_id`, `ruangan_id`, `nama_barang`, `kondisi`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'MikroTik RB750Gr3', 'baik', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(2, 2, 1, 'Cisco Catalyst 2960', 'baik', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(3, 8, 1, 'Tang Crimping RJ45', 'kurang_baik', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(4, 1, 2, 'Cisco Router 2901', 'rusak_berat', '2026-05-05 05:44:52', '2026-05-05 05:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

DROP TABLE IF EXISTS `barang_keluar`;
CREATE TABLE `barang_keluar` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjam_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tenggat_pinjam` date NOT NULL,
  `status` enum('dipinjam','dikembalikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

DROP TABLE IF EXISTS `barang_masuk`;
CREATE TABLE `barang_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `kondisi_masuk` enum('baik','kurang_baik','rusak_berat','dalam_perbaikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `item_movements`
--

DROP TABLE IF EXISTS `item_movements`;
CREATE TABLE `item_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_location_id` bigint UNSIGNED DEFAULT NULL,
  `to_location_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` bigint UNSIGNED NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `movement_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` bigint UNSIGNED NOT NULL,
  `reserved_at` bigint UNSIGNED DEFAULT NULL,
  `available_at` bigint UNSIGNED NOT NULL,
  `created_at` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` bigint UNSIGNED NOT NULL,
  `pending_jobs` bigint UNSIGNED NOT NULL,
  `failed_jobs` bigint UNSIGNED NOT NULL,
  `failed_job_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` bigint UNSIGNED DEFAULT NULL,
  `created_at` bigint UNSIGNED NOT NULL,
  `finished_at` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE `jurusan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jurusan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Router', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(2, 'Switch', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(3, 'Access Point', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(4, 'Kabel & Konektor', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(5, 'Server', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(6, 'Komputer', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(7, 'Monitor', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(8, 'Tools & Alat Ukur', '2026-05-05 05:44:52', '2026-05-05 05:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Lab Jaringan 1', 'LAB-JR1', 'Laboratorium Jaringan Dasar', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(2, 'Lab Jaringan 2', 'LAB-JR2', 'Laboratorium Jaringan Lanjut', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(3, 'Lab Komputer 1', 'LAB-KP1', 'Laboratorium Komputer Umum', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(4, 'Lab Server', 'LAB-SVR', 'Ruang Server NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(5, 'Gudang NOC', 'GDG-NOC', 'Gudang penyimpanan peralatan NOC', '2026-04-23 14:56:44', '2026-04-23 14:56:44'),
(6, 'Ruang Instruktur', 'RNG-INS', 'Ruang kerja instruktur', '2026-04-23 14:56:44', '2026-04-23 14:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_23_000001_create_categories_table', 1),
(5, '2026_04_23_000002_create_locations_table', 1),
(6, '2026_04_23_000003_create_items_table', 1),
(7, '2026_04_23_000004_create_item_movements_table', 1),
(8, '2026_04_23_181050_add_details_to_users_table', 2);


-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE `pemasok` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_pemasok` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pic` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian_barang`
--

DROP TABLE IF EXISTS `pengembalian_barang`;
CREATE TABLE `pengembalian_barang` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_kembali` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_keluar_id` bigint UNSIGNED NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `kondisi_kembali` enum('baik','kurang_baik','rusak_berat','dalam_perbaikan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peran` enum('Super_Admin','Admin','Kepala_Bengkel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `status` enum('Aktif','Nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `username`, `email`, `password`, `peran`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin NOC', 'admin', 'admin@noc.smkn4malang.sch.id', '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', 'Admin', 'Aktif', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(2, 'Super Admin', 'superadmin', 'superadmin@noc.smkn4malang.sch.id', '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', 'Super_Admin', 'Aktif', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(3, 'Bagus Ganteng', 'gusgus', 'bagus@example.com', '$2y$12$ewMkrwuzmWhRyGFNWtixB.SBxfmXW0JNHQlKAhEUOZ0mhiiTGdGk.', 'Kepala_Bengkel', 'Aktif', '2026-05-05 05:44:52', '2026-05-05 05:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
CREATE TABLE `ruangan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_ruangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ruangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penanggung_jawab_id` bigint UNSIGNED DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `kode_ruangan`, `nama_ruangan`, `penanggung_jawab_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'LAB-JR1', 'Lab Jaringan 1', NULL, 'Laboratorium Jaringan Dasar', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(2, 'LAB-JR2', 'Lab Jaringan 2', NULL, 'Laboratorium Jaringan Lanjut', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(3, 'LAB-SVR', 'Lab Server', NULL, 'Ruang Server NOC', '2026-05-05 05:44:52', '2026-05-05 05:44:52'),
(4, 'GDG-NOC', 'Gudang NOC', NULL, 'Gudang penyimpanan peralatan', '2026-05-05 05:44:52', '2026-05-05 05:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `username`, `role`, `is_active`) VALUES
(1, 'Admin NOC', 'admin@noc.smkn4malang.sch.id', '2026-04-23 14:56:43', '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', 'mxPUu6cSD3ZQfN1GrenJzNvThFEi8XIuK8f34yzzZsxQ8iaKRgN41rCoYSAJ', '2026-04-23 14:56:44', '2026-04-23 15:31:57', NULL, 'Admin', 1),
(2, 'Andika Galon Lima Liter', 'andika@gmail.com', NULL, '$2y$12$/.NBAODTvaRxqsPJ.ruEYeHMc5pWmyILBG17ar8asrHz/zZZUecMC', NULL, '2026-04-23 18:21:29', '2026-04-23 18:21:29', 'Adidika', 'User', 1),
(3, 'Bagus Ganteng', 'bagus@example.com', NULL, '$2y$12$ewMkrwuzmWhRyGFNWtixB.SBxfmXW0JNHQlKAhEUOZ0mhiiTGdGk.', NULL, '2026-04-24 06:38:24', '2026-04-24 06:38:24', 'gusgus', 'User', 1),
(4, 'Superadmin NOC', 'superadmin@noc.smkn4malang.sch.id', NULL, '$2y$12$6v/dUIqiBjS6hMfnt56n3O9jl/wRV9rYabgecks/st4vmNi5MVSgK', NULL, '2026-04-25 18:48:00', '2026-04-25 18:48:00', 'superadmin', 'Superadmin', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_kategori_id_foreign` (`kategori_id`),
  ADD KEY `barang_ruangan_id_foreign` (`ruangan_id`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_keluar_peminjam_id_foreign` (`peminjam_id`),
  ADD KEY `barang_keluar_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_masuk_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `fk_items_location_id` (`location_id`),
  ADD KEY `fk_items_category_id` (`category_id`);

--
-- Indexes for table `item_movements`
--
ALTER TABLE `item_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_movements_to_location_id` (`to_location_id`),
  ADD KEY `fk_item_movements_from_location_id` (`from_location_id`),
  ADD KEY `fk_item_movements_user_id` (`user_id`),
  ADD KEY `fk_item_movements_item_id` (`item_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_code_unique` (`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pemasok`
--
ALTER TABLE `pemasok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengembalian_kode_unik` (`kode_kembali`),
  ADD KEY `pengembalian_barang_keluar_id_foreign` (`barang_keluar_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengguna_username_unik` (`username`),
  ADD UNIQUE KEY `pengguna_email_unik` (`email`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruangan_kode_unik` (`kode_ruangan`),
  ADD KEY `ruangan_pj_id_foreign` (`penanggung_jawab_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item_movements`
--
ALTER TABLE `item_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `barang_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `barang_keluar_peminjam_id_foreign` FOREIGN KEY (`peminjam_id`) REFERENCES `pengguna` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_items_location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_movements`
--
ALTER TABLE `item_movements`
  ADD CONSTRAINT `fk_item_movements_from_location_id` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_item_movements_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_movements_to_location_id` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_item_movements_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengembalian_barang`
--
ALTER TABLE `pengembalian_barang`
  ADD CONSTRAINT `pengembalian_barang_keluar_id_foreign` FOREIGN KEY (`barang_keluar_id`) REFERENCES `barang_keluar` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD CONSTRAINT `ruangan_pj_id_foreign` FOREIGN KEY (`penanggung_jawab_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
