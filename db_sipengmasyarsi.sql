-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 21, 2026 at 03:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sipengmasyarsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Teknologi Informasi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, 'Kedokteran', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(3, 'Kedokteran Gigi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(4, 'Ekonomi dan Bisnis', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(5, 'Hukum', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(6, 'Psikologi', '2026-01-21 02:58:12', '2026-01-21 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `fakultas_prodis`
--

CREATE TABLE `fakultas_prodis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fakultas_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas_prodis`
--

INSERT INTO `fakultas_prodis` (`id`, `fakultas_id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, 'Teknik Informatika', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, 1, 'Perpustakaan & Sains Informasi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(3, 2, 'Kedokteran Umum', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(4, 3, 'Kedokteran Gigi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(5, 4, 'Manajemen', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(6, 4, 'Akuntansi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(7, 5, 'Ilmu Hukum', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(8, 6, 'Psikologi', '2026-01-21 02:58:12', '2026-01-21 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Lektor', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, 'Lektor Kepala', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(3, 'Guru Besar(Profesor)', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(4, 'Asisten Ahli', '2026-01-21 02:58:12', '2026-01-21 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_13_115203_create_panduan_table', 1),
(5, '2025_12_16_091554_create_proposal_table', 1),
(6, '2025_12_16_123157_create_proposal_core_anggota_dosen_table', 1),
(7, '2025_12_16_123157_create_proposal_core_anggota_mahasiswa_table', 1),
(8, '2025_12_16_123157_create_proposal_core_atribut_table', 1),
(9, '2025_12_16_123157_create_proposal_core_biaya_table', 1),
(10, '2025_12_16_123157_create_proposal_core_identitas_table', 1),
(11, '2025_12_16_123157_create_proposal_core_uraian_table', 1),
(12, '2025_12_16_123158_create_proposal_core_pengesahan_table', 1),
(13, '2025_12_20_104328_create_proposal_lampirans_table', 1),
(14, '2026_01_05_103121_create_notifications_table', 1),
(15, '2026_01_15_013649_create_skemas_table', 1),
(16, '2026_01_15_022051_create_skalas_table', 1),
(17, '2026_01_15_022146_modify_skemas_table_add_skala_id', 1),
(18, '2026_01_15_102327_create_prodis_table', 1),
(19, '2026_01_16_210759_create_fakultas_table', 1),
(20, '2026_01_16_210841_create_fakultas_prodis_table', 1),
(21, '2026_01_16_210845_create_jabatans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panduan`
--

CREATE TABLE `panduan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `panduan`
--

INSERT INTO `panduan` (`id`, `title`, `file`, `created_at`, `updated_at`) VALUES
(1, 'Panduan Mengajukan Proposal', 'Panduan mengajukan proposal.pdf', '2026-01-21 02:58:45', '2026-01-21 02:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `prodis`
--

CREATE TABLE `prodis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodis`
--

INSERT INTO `prodis` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Teknik Informatika', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, 'Perpustakaan dan Sains Informasi', '2026-01-21 02:58:12', '2026-01-21 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_pelaksanaan` year(4) NOT NULL,
  `skala_pelaksanaan` varchar(50) NOT NULL,
  `skema` tinyint(3) UNSIGNED NOT NULL,
  `status_progress` int(11) NOT NULL DEFAULT 0,
  `file_proposal` varchar(255) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_anggota_dosen`
--

CREATE TABLE `proposal_core_anggota_dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `peran` varchar(50) NOT NULL,
  `is_approved_dosen` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_anggota_mahasiswa`
--

CREATE TABLE `proposal_core_anggota_mahasiswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `npm` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `peran` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_atribut`
--

CREATE TABLE `proposal_core_atribut` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `rumpun_ilmu` varchar(50) NOT NULL,
  `nama_institusi_mitra` varchar(50) NOT NULL,
  `penanggung_jawab_mitra` varchar(50) NOT NULL,
  `alamat_mitra` varchar(250) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_biaya`
--

CREATE TABLE `proposal_core_biaya` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `honor_output` bigint(20) UNSIGNED NOT NULL,
  `belanja_non_operasional` bigint(20) UNSIGNED NOT NULL,
  `bahan_habis_pakai` bigint(20) UNSIGNED NOT NULL,
  `transportasi` bigint(20) UNSIGNED NOT NULL,
  `jumlah_tendik` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_identitas`
--

CREATE TABLE `proposal_core_identitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(300) NOT NULL,
  `abstrak` text NOT NULL,
  `keyword` varchar(150) NOT NULL,
  `periode_kegiatan` tinyint(3) UNSIGNED NOT NULL,
  `bidang_fokus` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_pengesahan`
--

CREATE TABLE `proposal_core_pengesahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `kota` varchar(50) NOT NULL,
  `jabatan_mengetahui` varchar(50) NOT NULL,
  `nama_mengetahui` varchar(50) NOT NULL,
  `nip_mengetahui` varchar(20) NOT NULL,
  `jabatan_menyetujui` varchar(50) NOT NULL,
  `nama_menyetujui` varchar(50) NOT NULL,
  `nip_menyetujui` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_core_uraian`
--

CREATE TABLE `proposal_core_uraian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `objek_pengabdian` varchar(50) NOT NULL,
  `instansi_terlibat` varchar(50) DEFAULT NULL,
  `lokasi_pengabdian` varchar(250) NOT NULL,
  `temuan_ditargetkan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposal_lampiran`
--

CREATE TABLE `proposal_lampiran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `kategori` enum('dokumen','artikel','sertifikat','hki') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BlMXnfJED9Ba7IotLKVSYsGkFeKQZTPZg5RfGonE', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnJabVl4Q21xVFJaRkJFWTRaYXFjWTNJQUpFb0I1bm11OU9lNURWVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768964325);

-- --------------------------------------------------------

--
-- Table structure for table `skalas`
--

CREATE TABLE `skalas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skalas`
--

INSERT INTO `skalas` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Prodi', '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, 'Pusat', '2026-01-21 02:58:12', '2026-01-21 02:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `skemas`
--

CREATE TABLE `skemas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `label_dropdown` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `skala_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skemas`
--

INSERT INTO `skemas` (`id`, `nama`, `label_dropdown`, `is_active`, `created_at`, `updated_at`, `skala_id`) VALUES
(1, 'Skema Program Internal Prodi Teknik Informatika', 'Teknik Informatika', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 1),
(2, 'Skema Program Internal Prodi Perpustakaan dan Sains Informasi', 'Perpustakaan dan Sains Informasi', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 1),
(3, 'Skema Program Pusat Peduli TB', 'Pusat YARSI Peduli TB', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 2),
(4, 'Skema Program YARSI Peduli HIV/AIDS', 'Pusat YARSI Peduli HIV/AIDS', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 2),
(5, 'Skema Program Pusat YARSI Pemberdayaan Desa', 'Pusat YARSI Pemberdayaan Desa', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 2),
(6, 'Skema Program Pusat YARSI Peduli Penglihatan', 'Pusat YARSI Peduli Penglihatan', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 2),
(7, 'Skema Program Pelayanan Keluarga Sejahtera (PPKS)', 'Pusat Pelayanan Keluarga Sejahtera (PPKS)', 1, '2026-01-21 02:58:12', '2026-01-21 02:58:12', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `prodi` varchar(255) DEFAULT NULL,
  `jabatan_fungsional` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Dosen Pengusul',
  `tanda_tangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `guid`, `domain`, `name`, `username`, `password`, `nidn`, `fakultas`, `prodi`, `jabatan_fungsional`, `role`, `tanda_tangan`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Administrator', 'admin', '$2y$12$dV3m.mIB5FaQlAONqr3/qe/hJvTxWTvQKsJ2yo4dl/rwQTXqhdWPm', NULL, NULL, NULL, NULL, 'Admin', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(2, NULL, NULL, 'Wakil Dekan 3', 'wadek', '$2y$12$oVjcjyaQlug1v4xj4BugRuCZjH7c8YQWcKoqCUjXLghwSbmAdZUVe', '111111111111', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Wakil Dekan 3', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(3, NULL, NULL, 'Dekan', 'dekan', '$2y$12$5SWRbCFaQEoPoG6x9.YCx.GYhdHUg1HtACPPOJO4heYinnBZr7RvC', '22222222222', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Dekan', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(4, NULL, NULL, 'Kepala Pusat Yarsi Peduli Penglihatan', 'pusat1', '$2y$12$IntddY5mDNSIDDMky90iyOEP6j.wO/EpekuxkYYPvj/O514K2Jv5W', '33333333333', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Kepala Pusat 1', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(5, NULL, NULL, 'Kepala Pusat Yarsi Peduli TB', 'pusat2', '$2y$12$rH37.W3h74JwGMQzccMrQuBVwP3lcqczTZUy0OedE6tOrqQCjLXBC', '443241412', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Kepala Pusat 2', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(6, NULL, NULL, 'Kepala Pusat Yarsi Pemberdayaan Desa', 'pusat3', '$2y$12$yKeq.5EbZLCKPwYLlULxneS.DLDC47UF2TPIfBJ8G7Um2SqUpFLFm', '55555555555', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Kepala Pusat 3', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(7, NULL, NULL, 'Kepala Pusat Yarsi Peduli HIV/AIDS', 'pusat4', '$2y$12$LpmS08CKCgW9AB2IO1f8re3ziXzsl5.T7L2htDLCfrAjWyjquAfyq', '66666666666', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Kepala Pusat 4', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(8, NULL, NULL, 'Kepala Pusat Yarsi Pelayanan Keluarga Sejahtera', 'pusat5', '$2y$12$JKCmyrGjNebkfx3xsz9tBeTah8zmTdUmTrNJl8OrhQW6MbyaWvTXe', '77777777777', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Kepala Pusat 5', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(9, NULL, NULL, 'Wakil Rektor 3', 'warek', '$2y$12$vel2H3v71XvntP0JFzQLZu/aM3M2N/Xo0/0I.avY6.uP7pSg.OOKC', '88888888888', 'Teknologi Informasi', 'Teknik Informatika', 'Asisten Ahli', 'Wakil Rektor 3', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12'),
(10, NULL, NULL, 'Dosen', 'dosen', '$2y$12$NvJSsCWLMPHrDrYcL2SryuFkLsf6.z/C1lM7vc3IxLPZgKnt1pTmu', '99999999999', 'Teknologi Informasi', 'Perpustakaan & Sains Informasi', 'Asisten Ahli', 'Dosen', NULL, '2026-01-21 02:58:12', '2026-01-21 02:58:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fakultas_prodis`
--
ALTER TABLE `fakultas_prodis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fakultas_prodis_fakultas_id_foreign` (`fakultas_id`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `panduan`
--
ALTER TABLE `panduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prodis`
--
ALTER TABLE `prodis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_user_id_foreign` (`user_id`);

--
-- Indexes for table `proposal_core_anggota_dosen`
--
ALTER TABLE `proposal_core_anggota_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_anggota_dosen_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_anggota_mahasiswa`
--
ALTER TABLE `proposal_core_anggota_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_anggota_mahasiswa_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_atribut`
--
ALTER TABLE `proposal_core_atribut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_atribut_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_biaya`
--
ALTER TABLE `proposal_core_biaya`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_biaya_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_identitas`
--
ALTER TABLE `proposal_core_identitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_identitas_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_pengesahan`
--
ALTER TABLE `proposal_core_pengesahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_pengesahan_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_core_uraian`
--
ALTER TABLE `proposal_core_uraian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_core_uraian_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `proposal_lampiran`
--
ALTER TABLE `proposal_lampiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_lampiran_proposal_id_foreign` (`proposal_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skalas`
--
ALTER TABLE `skalas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skalas_nama_unique` (`nama`);

--
-- Indexes for table `skemas`
--
ALTER TABLE `skemas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skemas_skala_id_foreign` (`skala_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_guid_unique` (`guid`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_nidn_unique` (`nidn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fakultas_prodis`
--
ALTER TABLE `fakultas_prodis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `panduan`
--
ALTER TABLE `panduan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prodis`
--
ALTER TABLE `prodis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_anggota_dosen`
--
ALTER TABLE `proposal_core_anggota_dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_anggota_mahasiswa`
--
ALTER TABLE `proposal_core_anggota_mahasiswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_atribut`
--
ALTER TABLE `proposal_core_atribut`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_biaya`
--
ALTER TABLE `proposal_core_biaya`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_identitas`
--
ALTER TABLE `proposal_core_identitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_pengesahan`
--
ALTER TABLE `proposal_core_pengesahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_core_uraian`
--
ALTER TABLE `proposal_core_uraian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposal_lampiran`
--
ALTER TABLE `proposal_lampiran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skalas`
--
ALTER TABLE `skalas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skemas`
--
ALTER TABLE `skemas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fakultas_prodis`
--
ALTER TABLE `fakultas_prodis`
  ADD CONSTRAINT `fakultas_prodis_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `proposal_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_anggota_dosen`
--
ALTER TABLE `proposal_core_anggota_dosen`
  ADD CONSTRAINT `proposal_core_anggota_dosen_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_anggota_mahasiswa`
--
ALTER TABLE `proposal_core_anggota_mahasiswa`
  ADD CONSTRAINT `proposal_core_anggota_mahasiswa_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_atribut`
--
ALTER TABLE `proposal_core_atribut`
  ADD CONSTRAINT `proposal_core_atribut_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_biaya`
--
ALTER TABLE `proposal_core_biaya`
  ADD CONSTRAINT `proposal_core_biaya_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_identitas`
--
ALTER TABLE `proposal_core_identitas`
  ADD CONSTRAINT `proposal_core_identitas_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_pengesahan`
--
ALTER TABLE `proposal_core_pengesahan`
  ADD CONSTRAINT `proposal_core_pengesahan_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_core_uraian`
--
ALTER TABLE `proposal_core_uraian`
  ADD CONSTRAINT `proposal_core_uraian_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposal_lampiran`
--
ALTER TABLE `proposal_lampiran`
  ADD CONSTRAINT `proposal_lampiran_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skemas`
--
ALTER TABLE `skemas`
  ADD CONSTRAINT `skemas_skala_id_foreign` FOREIGN KEY (`skala_id`) REFERENCES `skalas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
