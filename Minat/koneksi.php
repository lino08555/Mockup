<?php
$host = 'localhost'; // Ganti dengan host jika berbeda
$username = 'root';  // Ganti dengan username MySQL
$password = '';      // Ganti dengan password MySQL
$database = 'spk_db'; // Ganti dengan nama database yang digunakan

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Mengecek apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
