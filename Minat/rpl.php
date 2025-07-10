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
$peminatan = "RPL";
$stmt->bind_param("s", $peminatan);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" href="logo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <title>Rekayasa Perangkat Lunak - Universitas Timor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff;
    color: #333;
    line-height: 1.6;
  }

  .page-title {
    text-align: center;
    font-size: 32px;
    font-weight: 600;
    margin: 40px 0 20px;
  }

  .container {
    max-width: 800px;
    margin: auto;
    padding: 0 20px;
  }

  .content {
    padding: 20px;
  }

  .flex-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .image-content img {
    max-width: 50%;
    border-radius: 8px;
  }

  .text-content p {
    font-size: 16px;
    margin-bottom: 10px;
    text-align: justify;
  }

  h2 {
    font-size: 22px;
    text-align: center;
    margin-top: 40px;
    color: #444;
  }

  table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    font-size: 15px;
  }

  th, td {
    padding: 10px 8px;
    border-bottom: 1px solid #ddd;
    text-align: center;
  }

  th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: 500;
  }

  tr:hover {
    background-color: #f9f9f9;
  }

  .btn {
    display: inline-block;
    margin-top: 30px;
    padding: 10px 20px;
    font-size: 14px;
    color: #333;
    background: none;
    border: 1px solid #ccc;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }

  .btn:hover {
    background-color: #eee;
  }

  @media (max-width: 600px) {
    .page-title {
      font-size: 24px;
    }

    h2 {
      font-size: 18px;
    }

    table {
      font-size: 13px;
    }
  }
</style>

</head>
<body>
  <h1 class="page-title">Peminatan Rekayasa Perangkat Lunak</h1>
  <div class="container">
    <div class="content">
      <div class="flex-container">
        <div class="image-content">
          <img src="rpl_image.jpg" alt="Ilustrasi RPL">
        </div>
        <div class="text-content">
          <p>Apa itu peminatan RPL (Rekayasa Perangkat Lunak)?</p>
          <p><strong>Rekayasa Perangkat Lunak (RPL)</strong> adalah bidang yang mempelajari bagaimana kita merancang, membuat, dan memelihara sebuah aplikasi atau software secara baik dan benar. Jadi, kalau kalian ingin membuat aplikasi untuk mobile maupun website seperti sistem informasi, absensi, kasir, atau game sederhana, inilah bidang yang tepat!</p>
          <p>Setelah masuk ke peminatan ini kamu akan belajar dengan mata kuliah seperti yang ada pada tabel berikut!</p>
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
