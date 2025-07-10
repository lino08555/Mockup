<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan user database kamu
$pass = ""; // Jika ada password, masukkan di sini
$dbname = "spk_db";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT * FROM akademik ORDER BY semester, id";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Mengubah data ke format JSON dan mengatur header
header('Content-Type: application/json');
echo json_encode($data);

// Menutup koneksi
$conn->close();
?>