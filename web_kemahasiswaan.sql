-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2026 pada 15.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_kemahasiswaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'LOGIN_SUCCESS', 'User Administrator (admin) berhasil masuk.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:51:56', '2026-06-02 22:51:56'),
(2, 1, 'LOGOUT', 'User Administrator berhasil keluar.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:52:23', '2026-06-02 22:52:23'),
(3, NULL, 'LOGIN_FAILED', 'Percobaan login gagal untuk email: budi@siakad.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:52:45', '2026-06-02 22:52:45'),
(4, 2, 'LOGIN_SUCCESS', 'User Dr. Budi Santoso (dosen) berhasil masuk.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:53:16', '2026-06-02 22:53:16'),
(5, 2, 'LOGOUT', 'User Dr. Budi Santoso berhasil keluar.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:53:32', '2026-06-02 22:53:32'),
(6, 4, 'LOGIN_SUCCESS', 'User Adi Wijaya (mahasiswa) berhasil masuk.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 22:53:52', '2026-06-02 22:53:52'),
(7, 4, 'ADD_KRS', 'Mahasiswa Adi Wijaya mengajukan KRS mata kuliah: Keamanan Siber (IF-301)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 23:00:24', '2026-06-02 23:00:24'),
(8, 4, 'LOGOUT', 'User Adi Wijaya berhasil keluar.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 23:00:36', '2026-06-02 23:00:36'),
(9, 1, 'LOGIN_SUCCESS', 'User Administrator (admin) berhasil masuk.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', '2026-06-02 23:01:14', '2026-06-02 23:01:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `user_id`, `nidn`, `nama`, `spesialisasi`, `telepon`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 2, '1111111111', 'Dr. Budi Santoso', 'Cyber Security', '081234567890', 'Sleman, Yogyakarta', '2026-06-02 22:44:22', '2026-06-02 22:44:22'),
(2, 3, '2222222222', 'Dr. Siti Aminah', 'Software Engineering', '081298765432', 'Bantul, Yogyakarta', '2026-06-02 22:44:22', '2026-06-02 22:44:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `hari` varchar(15) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id`, `mata_kuliah_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Senin', '08:00:00', '10:30:00', 'Lab Cyber Security', '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(2, 2, 2, 'Selasa', '10:00:00', '12:30:00', 'Lab Web & Software', '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(3, 3, 2, 'Rabu', '13:00:00', '15:30:00', 'Ruang Teori R-202', '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(4, 4, 1, 'Kamis', '08:00:00', '10:30:00', 'Lab Jaringan', '2026-06-02 22:44:24', '2026-06-02 22:44:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mahasiswa_id` bigint(20) UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_akademik` varchar(15) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `status_approval` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `tahun_akademik`, `semester`, `status_approval`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025/2026', 'Ganjil', 'approved', '2026-06-02 23:00:24', '2026-06-02 23:00:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `user_id`, `nim`, `nama`, `jurusan`, `angkatan`, `telepon`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 4, '240001', 'Adi Wijaya', 'Teknik Informatika', 2024, '082134567801', 'Depok, Sleman', '2026-06-02 22:44:23', '2026-06-02 22:44:23'),
(2, 5, '240002', 'Citra Lestari', 'Sistem Informasi', 2024, '082134567802', 'Gondokusuman, Kota Yogyakarta', '2026-06-02 22:44:23', '2026-06-02 22:44:23'),
(3, 6, '240003', 'Dewi Pratama', 'Teknik Informatika', 2024, '082134567803', 'Kasihan, Bantul', '2026-06-02 22:44:24', '2026-06-02 22:44:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `semester`, `created_at`, `updated_at`) VALUES
(1, 'IF-301', 'Keamanan Siber', 3, 4, '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(2, 'IF-302', 'Pemrograman Web Laravel', 3, 4, '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(3, 'IF-303', 'Basis Data Lanjut', 3, 4, '2026-06-02 22:44:24', '2026-06-02 22:44:24'),
(4, 'IF-304', 'Jaringan Komputer', 3, 4, '2026-06-02 22:44:24', '2026-06-02 22:44:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_03_054237_create_academic_tables', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `krs_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_angka` int(11) DEFAULT NULL,
  `nilai_huruf` varchar(5) DEFAULT NULL,
  `dosen_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cwRyWPiVBQFYSF28ugB1vLhlKLyfZnQ2obsBIHrL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkpvajRUUHFXOW94M2xXQ1RzR3BzN3Z4WWVkS3luNDZpNFBsQzc3TCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO319', 1780476014),
('F7qPtuZjjERG1wEQ0sj5zujz9cH3L7jaiW15j41E', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUVdlTU83MExSQ0tMbUt6UXdWbGVhUGI4OHVEYWdsdjNpTnhCeGpRNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1780466475),
('xQEOIb2Dk4BgWKKmJa9AHLYXzBSNmXM0ZASyfty4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSE8xWU9VQzZJNG0xRnBTb2dYQ0ZtelZIa1hGM3l6dUxjQW01RWdrTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780476268);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'mahasiswa',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@siakad.com', NULL, '$2y$12$P/F73MJdgzj2NiCfua0q/uWZI3/zpG2JFPeg2N9Ms3.sLJP51GLJC', NULL, 'admin', 'active', '2026-06-02 22:44:21', '2026-06-02 22:44:21'),
(2, 'Dr. Budi Santoso', 'budi@siakad.com', NULL, '$2y$12$tFPQ3R8/h2CDHf9fOppOL.DwikF/s45WGyu71jb5dENQJ8ZnO8Lmq', NULL, 'dosen', 'active', '2026-06-02 22:44:21', '2026-06-02 22:44:21'),
(3, 'Dr. Siti Aminah', 'siti@siakad.com', NULL, '$2y$12$IjRn1McEBrU4VgWy9mJQz.8hiAgiFWm9433badNPGb.zTNAGFICa2', NULL, 'dosen', 'active', '2026-06-02 22:44:22', '2026-06-02 22:44:22'),
(4, 'Adi Wijaya', 'adi@siakad.com', NULL, '$2y$12$jNlczvsVlVGdP1HUyAf/8ejb2AakXOVtDhI.CPnou1kz.iWFDwKye', NULL, 'mahasiswa', 'active', '2026-06-02 22:44:23', '2026-06-02 22:44:23'),
(5, 'Citra Lestari', 'citra@siakad.com', NULL, '$2y$12$bdSJgK0lguWBpmo3YcfXceIBvLoJErq93yBthiUhQ4Yd0UtkxfPJi', NULL, 'mahasiswa', 'active', '2026-06-02 22:44:23', '2026-06-02 22:44:23'),
(6, 'Dewi Pratama', 'dewi@siakad.com', NULL, '$2y$12$MLFDLmkD.Qgx6YjTH3ieWObZOSFAneGFsdQnWJxHB1GjnGx0qygPq', NULL, 'mahasiswa', 'active', '2026-06-02 22:44:23', '2026-06-02 22:44:23');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_nidn_unique` (`nidn`),
  ADD KEY `dosen_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `jadwal_dosen_id_foreign` (`dosen_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `krs_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `krs_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswa_nim_unique` (`nim`),
  ADD KEY `mahasiswa_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliah_kode_mk_unique` (`kode_mk`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilai_krs_id_foreign` (`krs_id`),
  ADD KEY `nilai_dosen_id_foreign` (`dosen_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_krs_id_foreign` FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
