<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk mencegah SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Memverifikasi password
        if (password_verify($password, $user['password'])) {
            // Menyimpan data pengguna ke dalam session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_npm'] = $user['npm'];
            $_SESSION['role'] = $user['role']; // Menggunakan kolom role

            // Mengarahkan ke halaman utama
            header("Location: index.php");
            exit(); // Menghentikan eksekusi script
        } else {
            // Password salah
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        // Email tidak ditemukan
        header("Location: login.php?error=email_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>