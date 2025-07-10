<?php
session_start();
include 'koneksi.php'; // file koneksi ke database

// Tangkap data dari form
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$nim = $_POST['nim'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = $_POST['role'];

// Validasi
if ($password != $confirm_password) {
    echo "<script>alert('Password tidak cocok!'); window.location='Register.php';</script>";
    exit;
}

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan ke database
$query = "INSERT INTO users (first_name, last_name, nim, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ssssss', $first_name, $last_name, $nim, $email, $hashed_password, $role);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='Login.php';</script>";
} else {
    echo "<script>alert('Registrasi gagal.'); window.location='Register.php';</script>";
}
?>
