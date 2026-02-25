-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 25 Feb 2026 pada 04.18
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
-- Database: `sistem_penilaian_magang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mahasiswa`
--

CREATE TABLE `tb_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `asal_kampus` varchar(100) DEFAULT NULL,
  `divisi` varchar(100) DEFAULT NULL,
  `periode_magang` varchar(50) DEFAULT NULL,
  `nomor_hp` varchar(15) DEFAULT NULL,
  `id_pembimbing` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_mahasiswa`
--

INSERT INTO `tb_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `asal_kampus`, `divisi`, `periode_magang`, `nomor_hp`, `id_pembimbing`) VALUES
(1, 'Komang Dwi Putra', '210030123', 'ITB STIKOM Bali', 'IT Support', 'Jan 2025 - Jun 2025', '082345678901', 1),
(2, 'Ni Luh Ayu', '210030124', 'Undiksha', 'IT Support', 'Jan 2025 - Jun 2025', '082345678902', 1),
(3, 'Luh Yu', '230010109', 'STIKOM Bali', 'IT Support', 'Jan 2025 - Jun 2025', '084723846274', 2),
(4, 'Yanto', '240020203', 'ITB STIKOM Bali', 'IT Support', 'Jan 2025 - Jun 2025', '0387494583473', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembimbing`
--

CREATE TABLE `tb_pembimbing` (
  `id_pembimbing` int NOT NULL,
  `nama_pembimbing` varchar(100) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `divisi` varchar(100) DEFAULT NULL,
  `nomor_hp` varchar(15) DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_pembimbing`
--

INSERT INTO `tb_pembimbing` (`id_pembimbing`, `nama_pembimbing`, `nip`, `jabatan`, `divisi`, `nomor_hp`, `id_user`) VALUES
(1, 'I Made Agus', '1234567890', 'Supervisor IT', 'IT Support', '081234567890', 5),
(2, 'I Made Wibawa', '1234567891', 'Supervisor IT', 'IT Support', '081234567891', 2),
(3, 'Ibu Sari', '1234567893', 'Supervisor IT', 'IT Support', '084723846274', NULL),
(4, 'Ayu Sulastri Ningsih', '1234567894', 'Supervisor IT', 'IT Support', '084723846275', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penilaian`
--

CREATE TABLE `tb_penilaian` (
  `id_penilaian` int NOT NULL,
  `nilai_disiplin` int DEFAULT NULL,
  `nilai_tanggung_jawab` int DEFAULT NULL,
  `nilai_etika` int DEFAULT NULL,
  `nilai_komunikasi` int DEFAULT NULL,
  `komentar` text,
  `id_mahasiswa` int NOT NULL,
  `id_pembimbing` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data untuk tabel `tb_penilaian`
--

INSERT INTO `tb_penilaian` (`id_penilaian`, `nilai_disiplin`, `nilai_tanggung_jawab`, `nilai_etika`, `nilai_komunikasi`, `komentar`, `id_mahasiswa`, `id_pembimbing`, `created_at`) VALUES
(7, 90, 85, 88, 92, 'Baik', 1, 1, '2026-02-22 08:48:46'),
(8, 80, 75, 78, 82, 'Cukup', 2, 1, '2026-02-22 08:48:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_role`
--

CREATE TABLE `tb_role` (
  `id_role` int NOT NULL,
  `nama_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_role`
--

INSERT INTO `tb_role` (`id_role`, `nama_role`) VALUES
(1, 'admin'),
(2, 'pembimbing');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `id_role`, `created_at`) VALUES
(1, 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, '2026-02-22 07:44:56'),
(2, 'pembimbing1', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, '2026-02-22 07:44:56'),
(5, 'pembimbing2', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, '2026-02-22 07:46:13'),
(6, 'pembimbing3', 'e10adc3949ba59abbe56e057f20f883e', 2, '2026-02-23 00:52:11');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_mahasiswa`
--
ALTER TABLE `tb_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `fk_mahasiswa_pembimbing` (`id_pembimbing`);

--
-- Indeks untuk tabel `tb_pembimbing`
--
ALTER TABLE `tb_pembimbing`
  ADD PRIMARY KEY (`id_pembimbing`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `tb_penilaian`
--
ALTER TABLE `tb_penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `fk_penilaian_mahasiswa` (`id_mahasiswa`),
  ADD KEY `fk_penilaian_pembimbing` (`id_pembimbing`);

--
-- Indeks untuk tabel `tb_role`
--
ALTER TABLE `tb_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_role` (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_mahasiswa`
--
ALTER TABLE `tb_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_pembimbing`
--
ALTER TABLE `tb_pembimbing`
  MODIFY `id_pembimbing` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_penilaian`
--
ALTER TABLE `tb_penilaian`
  MODIFY `id_penilaian` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_role`
--
ALTER TABLE `tb_role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_mahasiswa`
--
ALTER TABLE `tb_mahasiswa`
  ADD CONSTRAINT `fk_mahasiswa_pembimbing` FOREIGN KEY (`id_pembimbing`) REFERENCES `tb_pembimbing` (`id_pembimbing`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_pembimbing`
--
ALTER TABLE `tb_pembimbing`
  ADD CONSTRAINT `fk_pembimbing_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_penilaian`
--
ALTER TABLE `tb_penilaian`
  ADD CONSTRAINT `fk_penilaian_mahasiswa` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tb_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penilaian_pembimbing` FOREIGN KEY (`id_pembimbing`) REFERENCES `tb_pembimbing` (`id_pembimbing`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `tb_role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
