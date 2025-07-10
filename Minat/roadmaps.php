<?php
// Di file roadmaps.php, tambahkan pengecekan login
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $_SESSION['message'] = "Silakan login untuk mengakses fitur ini.";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" href="logo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Roadmaps Konsentrasi Informatika</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 40px;
      background-color: #f9f9f9;
    }

    h2 {
      margin-bottom: 5px;
    }

    p.subtitle {
      color: #666;
      margin-bottom: 40px;
    }

    .cards-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      background-color: white;
      width: 280px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card video {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }

    .card-title {
      padding: 15px;
      font-weight: bold;
      font-size: 16px;
    }


  </style>
</head>
<body>

  <h2>Roadmaps Peminatan Teknologi Informasi</h2>
  <p class="subtitle">Eksplorasi mata kuliah peminatan apa saja yang ada di Program Studi Teknologi Informasi Universitas Timor</p>

  <div class="cards-container">
    <a href="rpl.php" class="card-link">
    <div class="card">
      <video src="rpl1.mp4" autoplay muted loop playsinline></video>
      <div class="card-title">Rekayasa Perangkat Lunak</div>
    </div>
    <div class="card">
      <a href="data_science.php" class="card-link">
      <video src="data1.mp4" autoplay muted loop playsinline></video>
      <div class="card-title">Data Science</div>
    </div>
    <div class="card">
      <a href="jaringan.php" class="card-link">
      <video src="data.mp4" autoplay muted loop playsinline></video>
      <div class="card-title">Sistem Jaringan Komputer</div>
    </div>
  </div>

</body>
</html>
