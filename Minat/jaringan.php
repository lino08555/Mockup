<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $_SESSION['message'] = "Silakan login untuk mengakses fitur ini.";
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php'; // pastikan file koneksi.php sudah dibuat dan berisi koneksi ke database

// Query daftar mata kuliah RPL
$stmt = $conn->prepare("SELECT * FROM akademik WHERE peminatan = ? ORDER BY semester ASC");
$peminatan = "Jaringan";
$stmt->bind_param("s", $peminatan);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" href="logo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <title>Jaringan Komputer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    /* (tetap sama seperti yang sudah kamu buat) */
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f4eae4;
      color: #4a1f1f;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .page-title {
      text-align: center;
      font-size: 48px;
      font-weight: 800;
      color: #5b1f1f;
      margin-top: 40px;
      margin-bottom: 20px;
      padding: 0 20px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      padding: 20px;
    }

    .content {
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .flex-container {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .text-content {
      flex: 1;
      min-width: 280px;
    }

    .text-content p {
      font-size: 18px;
      line-height: 1.7;
      text-align: justify;
    }

    .image-content {
      flex: 0 0 auto;
    }

    .image-content img {
      max-width: 400px;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .btn {
      display: inline-block;
      padding: 12px 24px;
      background-color: transparent;
      color: #5b1f1f;
      border: 2px solid #5b1f1f;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      transition: all 0.3s ease;
      margin-top: 20px;
    }

    .btn:hover {
      background-color: #5b1f1f;
      color: white;
      box-shadow: 0 4px 10px rgba(91, 31, 31, 0.2);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    th, td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ddd;
    }

    th {
      background-color: #5b1f1f;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 36px;
      }
      .container {
        padding: 20px;
      }
      .flex-container {
        flex-direction: column;
        align-items: center;
      }
      .image-content img {
        max-width: 80%;
      }
    }
  </style>
</head>
<body>
  <h1 class="page-title">Peminatan Jaringan</h1>
  <div class="container">
    <div class="content">
      <div class="flex-container">
        <div class="image-content">
          <img src="jaringan.jpg" alt="Ilustrasi RPL">
        </div>
        <div class="text-content">
          <p>Di peminatan Jaringan Komputer, kita akan belajar bagaimana menghubungkan komputer satu dengan lainnya agar bisa saling bertukar data. Kalian akan belajar bagaimana merancang, membangun, dan mengatur jaringan agar dapat digunakan secara aman dan efisien. Kita juga akan belajar tentang perangkat jaringan seperti router, switch, hub, dan kabel. Selain itu, kalian akan mempelajari cara mengamankan jaringan agar tidak mudah diretas, misalnya dengan menggunakan firewall dan enkripsi. Peminatan ini sangat penting bagi kalian yang ingin bekerja di bidang IT support, administrator jaringan, atau teknisi jaringan. Jadi, kalau kalian ingin tahu bagaimana komputer bisa terhubung satu sama lain dan bagaimana internet bekerja, peminatan Jaringan Komputer ini sangat cocok untuk kalian!</p>
        </div>
      </div>

      <!-- Daftar Mata Kuliah -->
      <h2 style="margin-top: 40px; text-align: center; color: #5b1f1f;">Daftar Mata Kuliah</h2>
      <table>
        <thead>
          <tr>
            <th>Semester</th>
            <th>Kode MK</th>
            <th>Mata Kuliah</th>
            <th>SKS</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['semester']; ?></td>
            <td><?php echo $row['kode_mk']; ?></td>
            <td><?php echo $row['mata_kuliah']; ?></td>
            <td><?php echo $row['sks']; ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <a href="javascript:history.back()" class="btn">← Kembali</a>
    </div>
  </div>
</body>
</html>
