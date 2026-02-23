-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 27 Jan 2026 pada 03.08
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arsip_nota`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota`
--

CREATE TABLE `nota` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `nomor_nota` varchar(50) NOT NULL,
  `tanggal_nota` date NOT NULL,
  `total_nominal` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `nota`
--

INSERT INTO `nota` (`id`, `admin_id`, `nomor_nota`, `tanggal_nota`, `total_nominal`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '2001', '2026-01-24', 200000.00, 'pending', '2026-01-24 03:45:11', '2026-01-24 03:45:11'),
(2, 3, '2002', '2026-01-24', 150000.00, 'approved', '2026-01-24 08:26:58', '2026-01-27 01:37:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota_approval`
--

CREATE TABLE `nota_approval` (
  `id` int NOT NULL,
  `nota_id` int NOT NULL,
  `manager_id` int NOT NULL,
  `status` enum('approved','rejected') NOT NULL,
  `catatan` text,
  `approved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `nota_approval`
--

INSERT INTO `nota_approval` (`id`, `nota_id`, `manager_id`, `status`, `catatan`, `approved_at`) VALUES
(1, 2, 4, 'approved', '', '2026-01-27 01:37:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota_files`
--

CREATE TABLE `nota_files` (
  `id` int NOT NULL,
  `nota_id` int NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('pdf','image') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `nota_files`
--

INSERT INTO `nota_files` (`id`, `nota_id`, `file_path`, `file_type`, `created_at`) VALUES
(1, 1, 'uploads/nota/nota_1_1769226311_0.png', 'image', '2026-01-24 03:45:11'),
(2, 2, 'uploads/nota/nota_2_1769243218_0.png', 'image', '2026-01-24 08:26:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'manager');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 1, 'Admin Satu', 'admin@company.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 'uploads/profile/1769257195_6974b8eb3363f.jpeg', 1, '2026-01-23 03:51:35', '2026-01-24 12:19:55'),
(4, 2, 'Manager Utama', 'manager@company.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 'uploads/profile/1769479410_69781cf2263b8.jpg', 1, '2026-01-23 03:51:35', '2026-01-27 02:03:30'),
(5, 1, 'Admin Dua', 'admin2@company.com', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, 1, '2026-01-27 01:58:42', '2026-01-27 01:58:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_nota` (`nomor_nota`),
  ADD KEY `fk_nota_admin` (`admin_id`);

--
-- Indeks untuk tabel `nota_approval`
--
ALTER TABLE `nota_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_approval_nota` (`nota_id`),
  ADD KEY `fk_approval_manager` (`manager_id`);

--
-- Indeks untuk tabel `nota_files`
--
ALTER TABLE `nota_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nota_files` (`nota_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_roles` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `nota`
--
ALTER TABLE `nota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `nota_approval`
--
ALTER TABLE `nota_approval`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `nota_files`
--
ALTER TABLE `nota_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `fk_nota_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nota_approval`
--
ALTER TABLE `nota_approval`
  ADD CONSTRAINT `fk_approval_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_approval_nota` FOREIGN KEY (`nota_id`) REFERENCES `nota` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nota_files`
--
ALTER TABLE `nota_files`
  ADD CONSTRAINT `fk_nota_files` FOREIGN KEY (`nota_id`) REFERENCES `nota` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
