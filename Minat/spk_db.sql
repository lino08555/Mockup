-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2025 pada 09.16
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
-- Database: `spk_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akademik`
--

CREATE TABLE `akademik` (
  `id` int(10) NOT NULL,
  `semester` int(11) NOT NULL,
  `kode_mk` text NOT NULL,
  `mata_kuliah` text NOT NULL,
  `sks` int(11) NOT NULL,
  `status` text NOT NULL,
  `peminatan` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akademik`
--

INSERT INTO `akademik` (`id`, `semester`, `kode_mk`, `mata_kuliah`, `sks`, `status`, `peminatan`) VALUES
(1, 1, 'FPT21101', 'Pengantar Teknologi Pertanian', 2, 'Wajib', ''),
(2, 1, 'MKU21010', 'Pendidikan Agama', 2, 'Wajib', ''),
(3, 1, 'MKU21105', 'Pancasila', 2, 'Wajib', ''),
(4, 1, 'TIF21101', 'Statistika dan Probabilitas', 2, 'Wajib', ''),
(5, 1, 'TIF31102', 'Pengantar Teknologi Informasi', 3, 'Wajib', ''),
(6, 1, 'TIF31103', 'Fisika Komputasi', 3, 'Wajib', ''),
(7, 1, 'TIF31104', 'Sistem Digital', 3, 'Wajib', ''),
(8, 1, 'TIF41105', 'Algoritma dan Struktur Data', 4, 'Wajib', ''),
(9, 2, 'FPT22102', 'Sistem Informasi Pertanian Lahan Kering', 2, 'Wajib', ''),
(10, 2, 'MKU22106', 'Kewarganegaraan', 2, 'Wajib', ''),
(11, 2, 'MKU22107', 'Filsafat Ilmu', 2, 'Wajib', ''),
(12, 2, 'MKU22108', 'Bahasa Inggris Komputer I', 2, 'Wajib', ''),
(13, 2, 'TIF32106', 'Desain dan Pengelolaan Jaringan', 3, 'Wajib', ''),
(14, 2, 'TIF32107', 'Analisis dan Desain Teknologi Informasi', 3, 'Wajib', ''),
(15, 2, 'TIF32109', 'Analisis dan Desain Berorientasi Objek', 3, 'Wajib', ''),
(16, 2, 'TIF42108', 'Sistem Basis Data', 4, 'Wajib', ''),
(17, 3, 'TIF33110', 'Metode Numerik', 3, 'Wajib', ''),
(18, 3, 'TIF33112', 'Organisasi dan Arsitektur Komputer', 3, 'Wajib', ''),
(19, 3, 'TIF33113', 'Pemrograman Berorientasi Objek', 3, 'Wajib', ''),
(20, 3, 'TIF33114', 'Teknologi Multimedia', 3, 'Wajib', ''),
(21, 3, 'TIF33115', 'Dasar Pemrograman Web', 3, 'Wajib', ''),
(22, 3, 'TIF33116', 'Rekayasa Perangkat Lunak', 3, 'Wajib', ''),
(23, 3, 'MKU23109', 'Bahasa Inggris Komputer 2', 2, 'Wajib', ''),
(24, 4, 'TIF34117', 'Interaksi Manusia dan Komputer', 3, 'Wajib', ''),
(25, 4, 'TIF34118', 'Manajemen Teknologi Informasi', 3, 'Wajib', ''),
(26, 4, 'TIF34119', 'Kecerdasan Buatan', 3, 'Wajib', ''),
(27, 4, 'TIF34120', 'Teknologi Informasi Geografis', 3, 'Wajib', ''),
(28, 4, 'TIF34121', 'Dasar Pemrograman Mobile', 3, 'Wajib', ''),
(29, 4, 'TIF34122', 'Sistem Berbasis Mikroprosesor', 3, 'Wajib', ''),
(30, 4, 'TIF34123', 'Aljabar Linear', 3, 'Wajib', ''),
(31, 4, 'TIF34124', 'Pengembangan WEB', 3, 'Wajib', ''),
(32, 5, 'TIF35124', 'Teknik Pemodelan dan Simulasi', 3, 'Wajib', ''),
(33, 5, 'TIF35126', 'Manajemen Proyek Teknologi Informasi', 3, 'Wajib', ''),
(34, 5, 'TIF35127', 'Arsitektur dan Integrasi Sistem', 3, 'Wajib', ''),
(35, 5, 'TIF35128', 'Audit dan Tata Kelola Teknologi Informasi', 3, 'Wajib', ''),
(36, 5, 'TIF35130', 'Kalkulus', 3, 'Wajib', ''),
(37, 5, 'TIF45124', 'Data Mining', 3, 'Wajib', ''),
(38, 6, 'TIF36129', 'Riset dan Metodologi Penelitian', 3, 'Wajib', ''),
(39, 6, 'TIF36131', 'Keamanan Teknologi Informasi', 3, 'Wajib', ''),
(40, 6, 'TIF36132', 'Teknologi Internet of Things (IoT)', 3, 'Wajib', ''),
(41, 6, 'TIF36139', 'Proyek Teknologi Informasi', 3, 'Wajib', ''),
(42, 7, 'MKU27107', 'Bahasa Indonesia', 2, 'Wajib', ''),
(43, 7, 'MKU37106', 'Kewirausahaan Teknologi Informasi', 3, 'Wajib', ''),
(44, 7, 'TIF27134', 'Etika Teknologi Informasi', 2, 'Wajib', ''),
(45, 7, 'TIF27135', 'Tugas Akhir 1', 2, 'Wajib', ''),
(46, 7, 'TIF37133', 'Praktek Kerja Lapangan', 3, 'Wajib', ''),
(47, 8, 'TIF48136', 'Tugas Akhir 2', 4, 'Wajib', ''),
(48, 7, 'TIF46212', 'Business Intelligence', 4, 'Pilihan', 'Data'),
(49, 7, 'TIF47224', 'Pengujian Perangkat Lunak', 4, 'Pilihan', 'RPL'),
(50, 7, 'TIF47234', 'Komputasi Awan', 4, 'Pilihan', 'Jaringan'),
(51, 5, 'TIF45211', 'Sistem Pengambilan Keputusan', 4, 'Pilihan', 'Data'),
(52, 5, 'TIF45221', 'Pengembangan Teknologi Aplikasi Mobile', 4, 'Pilihan', 'RPL'),
(53, 5, 'TIF45231', 'Jaringan Sensor Nirkabel', 4, 'Pilihan', 'Jaringan'),
(54, 6, 'TIF46213', 'Big Data dan Analitik', 4, 'Pilihan', 'Data'),
(55, 6, 'TIF46222', 'Pengembangan Teknologi Aplikasi Web', 4, 'Pilihan', 'RPL'),
(56, 6, 'TIF46223', 'Arsitektur Perangkat Lunak', 4, 'Pilihan', 'RPL'),
(57, 6, 'TIF46232', 'Forensik Komputer dan jaringan', 4, 'Pilihan', 'Jaringan'),
(58, 6, 'TIF46233', 'Keamanan dan Integritas Data', 4, 'Pilihan', 'Jaringan'),
(59, 6, 'TIF47214', 'Pengolahan Citra', 4, 'Pilihan', 'Data');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'Rekayasa Perangkat Lunak', 'Konsentrasi yang fokus pada pengembangan perangkat lunak dan aplikasi.', '2025-05-05 05:08:35'),
(2, 'Data Science', 'Konsentrasi yang fokus pada analisis data dan pengembangan model prediktif.', '2025-05-05 05:08:35'),
(3, 'Jaringan Komputer', 'Konsentrasi yang fokus pada desain dan pengelolaan jaringan komputer.', '2025-05-05 05:08:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_kuis`
--

CREATE TABLE `jawaban_kuis` (
  `id_jawabankuis` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_pertanyaan` int(11) DEFAULT NULL,
  `jawaban` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_matkul`
--

CREATE TABLE `jawaban_matkul` (
  `id_jawabanmatkul` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_matkul` int(11) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama_kriteria` varchar(255) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode`, `nama_kriteria`, `bobot`, `jenis`, `created_at`) VALUES
(1, 'C1', 'Rekayasa Perangkat Lunak', 0.10, 'benefit', '2025-05-05 05:08:35'),
(2, 'C2', 'Analisis dan Desain Berorientasi Objek', 0.08, 'benefit', '2025-05-05 05:08:35'),
(3, 'C3', 'Analisis dan Desain Teknologi Informasi', 0.08, 'benefit', '2025-05-05 05:08:35'),
(4, 'C4', 'Pengembangan Web', 0.10, 'benefit', '2025-05-05 05:08:35'),
(5, 'C5', 'Dasar Pemrograman Web', 0.10, 'benefit', '2025-05-05 05:08:35'),
(6, 'C6', 'Dasar Pemrograman Mobile', 0.10, 'benefit', '2025-05-05 05:08:35'),
(7, 'C7', 'Desain dan Pengelolaan Jaringan', 0.00, 'benefit', '2025-05-05 05:08:35'),
(8, 'C8', 'Sistem Digital', 0.08, 'benefit', '2025-05-05 05:08:35'),
(9, 'C9', 'Organisasi dan Arsitektur Komputer', 0.08, 'benefit', '2025-05-05 05:08:35'),
(10, 'C10', 'Sistem Berbasis Mikroprosesor', 0.08, 'benefit', '2025-05-05 05:08:35'),
(11, 'C11', 'Statistika dan Probabilitas', 0.08, 'benefit', '2025-05-05 05:08:35'),
(12, 'C12', 'Metode Numerik', 0.08, 'benefit', '2025-05-05 05:08:35'),
(13, 'C13', 'Kecerdasan Buatan', 0.10, 'benefit', '2025-05-05 05:08:35'),
(14, 'C14', 'Sistem Basis Data', 0.10, 'benefit', '2025-05-05 05:08:35'),
(15, 'C15', 'Peminatan (Quiz)', 0.10, 'benefit', '2025-05-05 05:08:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `matkul`
--

CREATE TABLE `matkul` (
  `id_matkul` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `matkul`
--

INSERT INTO `matkul` (`id_matkul`, `kode`, `nama`) VALUES
(1, 'C1', 'Rekayasa Perangkat Lunak'),
(2, 'C2', 'Analisis dan Desain Berorientasi Objek'),
(3, 'C3', 'Analisis dan Desain Teknologi Informasi'),
(4, 'C4', 'Pengembangan Web'),
(5, 'C5', 'Dasar Pemrograman Web'),
(6, 'C6', 'Dasar Pemrograman Mobile'),
(7, 'C7', 'Desain dan Pengelolaan Jaringan'),
(8, 'C8', 'Sistem Digital'),
(9, 'C9', 'Organisasi dan Arsitektur Komputer'),
(10, 'C10', 'Sistem Berbasis Mikroprosesor'),
(11, 'C11', 'Statistika dan Probabilitas'),
(12, 'C12', 'Metode Numerik'),
(13, 'C13', 'Kecerdasan Buatan'),
(14, 'C14', 'Sistem Basis Data');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian_alternatif`
--

CREATE TABLE `penilaian_alternatif` (
  `id_penilaian` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penilaian_alternatif`
--

INSERT INTO `penilaian_alternatif` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 8),
(3, 1, 3, 10),
(4, 1, 4, 10),
(5, 1, 5, 10),
(6, 1, 6, 10),
(7, 1, 7, 0),
(8, 1, 8, 8),
(9, 1, 9, 8),
(10, 1, 10, 8),
(11, 1, 11, 8),
(12, 1, 12, 8),
(13, 1, 13, 10),
(14, 1, 14, 10),
(15, 1, 15, 10),
(16, 2, 1, 0),
(17, 2, 2, 10),
(18, 2, 3, 0),
(19, 2, 4, 0),
(20, 2, 5, 0),
(21, 2, 6, 0),
(22, 2, 7, 0),
(23, 2, 8, 8),
(24, 2, 9, 8),
(25, 2, 10, 8),
(26, 2, 11, 10),
(27, 2, 12, 10),
(28, 2, 13, 10),
(29, 2, 14, 10),
(30, 2, 15, 10),
(31, 3, 1, 0),
(32, 3, 2, 8),
(33, 3, 3, 8),
(34, 3, 4, 8),
(35, 3, 5, 8),
(36, 3, 6, 0),
(37, 3, 7, 10),
(38, 3, 8, 10),
(39, 3, 9, 10),
(40, 3, 10, 10),
(41, 3, 11, 8),
(42, 3, 12, 8),
(43, 3, 13, 8),
(44, 3, 14, 8),
(45, 3, 15, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` int(11) NOT NULL,
  `teks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pertanyaan`
--

INSERT INTO `pertanyaan` (`id_pertanyaan`, `teks`) VALUES
(1, 'Apakah Anda suka menganalisis data untuk mendukung keputusan bisnis?'),
(2, 'Seberapa tertarik Anda dalam membuat perangkat lunak berbasis database?'),
(3, 'Seberapa besar minat Anda dalam bidang Big Data dan analisis data besar?'),
(4, 'Seberapa antusias Anda dalam menerapkan teknik Data Mining?'),
(5, 'Apakah Anda berminat dalam pembuatan Data Warehouse untuk pengolahan data besar?'),
(6, 'Seberapa tertarik Anda mempelajari sistem pendukung keputusan (DSS)?'),
(7, 'Apakah Anda suka mengembangkan algoritma berbasis data?'),
(8, 'Seberapa besar ketertarikan Anda terhadap Machine Learning untuk analisis data?'),
(9, 'Seberapa tertarik Anda dengan pembuatan laporan analitik berbasis dashboard?'),
(10, 'Apakah Anda ingin bekerja di bidang Business Intelligence?'),
(11, 'Seberapa besar minat Anda dalam optimasi pengambilan keputusan berbasis data?'),
(12, 'Apakah Anda menyukai eksplorasi database dalam proyek nyata?'),
(13, 'Seberapa besar ketertarikan Anda pada teknik visualisasi data?'),
(14, 'Apakah Anda ingin berkarir sebagai Data Analyst atau Data Engineer?'),
(15, 'Seberapa berminat Anda mengembangkan solusi berbasis Artificial Intelligence untuk bisnis?');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `first_name`, `last_name`, `nim`, `email`, `password`, `role`, `created_at`) VALUES
(14, 'Marcelino ', 'Correia', '51220059', 'marcelinocorreia08@gmail.com', '$2y$10$zlgXPQFlBXs8uJTo1cXq4uW9ucIWKOHG3w9X/selX48Nievf7jxd.', 'admin', '2025-06-02 06:11:19'),
(15, 'Riza', 'Akoit', '51220132', 'rizaakoit15@gmail.com', '$2y$10$usIYOsrZQyyq9/5Yv4JCv.eGxmpGdEX/nx8s9w8dKMP2Eodd2tuoK', 'user', '2025-06-02 07:07:00'),
(16, 'Novita', 'Marsal', '51220118', 'novymarchal@gmail.com', '$2y$10$2nMeIki1dGmjw8U5KCwdH.PkJrsMrKYIWVcUdjZEpxOJAiFEio6ua', 'user', '2025-06-02 15:37:36'),
(17, 'Chrisntsia ', 'Kolo', '51220046', 'icenkolo@gmail.com', '$2y$10$uPquVASkyhVKOWU04GrkrebS0sKTHLqV8oeEePXEgOlX90LzAq7aK', 'user', '2025-06-04 06:34:11');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akademik`
--
ALTER TABLE `akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `jawaban_kuis`
--
ALTER TABLE `jawaban_kuis`
  ADD PRIMARY KEY (`id_jawabankuis`),
  ADD KEY `user_id` (`id_user`),
  ADD KEY `pertanyaan_id` (`id_pertanyaan`);

--
-- Indeks untuk tabel `jawaban_matkul`
--
ALTER TABLE `jawaban_matkul`
  ADD PRIMARY KEY (`id_jawabanmatkul`),
  ADD KEY `user_id` (`id_user`),
  ADD KEY `matkul_id` (`id_matkul`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `matkul`
--
ALTER TABLE `matkul`
  ADD PRIMARY KEY (`id_matkul`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `penilaian_alternatif`
--
ALTER TABLE `penilaian_alternatif`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `alternatif_id` (`id_alternatif`),
  ADD KEY `kriteria_id` (`id_kriteria`);

--
-- Indeks untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akademik`
--
ALTER TABLE `akademik`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `jawaban_kuis`
--
ALTER TABLE `jawaban_kuis`
  MODIFY `id_jawabankuis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jawaban_matkul`
--
ALTER TABLE `jawaban_matkul`
  MODIFY `id_jawabanmatkul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `matkul`
--
ALTER TABLE `matkul`
  MODIFY `id_matkul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `penilaian_alternatif`
--
ALTER TABLE `penilaian_alternatif`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jawaban_kuis`
--
ALTER TABLE `jawaban_kuis`
  ADD CONSTRAINT `jawaban_kuis_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `jawaban_kuis_ibfk_2` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id_pertanyaan`),
  ADD CONSTRAINT `jawaban_kuis_ibfk_3` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan` (`id_pertanyaan`);

--
-- Ketidakleluasaan untuk tabel `jawaban_matkul`
--
ALTER TABLE `jawaban_matkul`
  ADD CONSTRAINT `jawaban_matkul_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `jawaban_matkul_ibfk_2` FOREIGN KEY (`id_matkul`) REFERENCES `matkul` (`id_matkul`),
  ADD CONSTRAINT `jawaban_matkul_ibfk_3` FOREIGN KEY (`id_matkul`) REFERENCES `matkul` (`id_matkul`);

--
-- Ketidakleluasaan untuk tabel `penilaian_alternatif`
--
ALTER TABLE `penilaian_alternatif`
  ADD CONSTRAINT `penilaian_alternatif_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`),
  ADD CONSTRAINT `penilaian_alternatif_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
