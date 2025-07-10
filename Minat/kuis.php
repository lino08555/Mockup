<?php
session_start();

$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true;

if (isset($_POST['theme-toggle'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] == 'dark') ? 'light' : 'dark';
}

$themeClass = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'bg-dark text-light dark-mode' : '';
$themeIcon = isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark' ? 'fas fa-sun' : 'fas fa-moon';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Konsentrasi</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body::-webkit-scrollbar {
            display: none;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: linear-gradient(to right, #561C24, #6D2932);
        }

        .custom-submit-btn {
            background-color: #6D2932;
            color: white;
            border: none;
        }

        .custom-submit-btn:hover {
            background-color: #561C24;
        }

        .question-card {
            margin-bottom: 40px;
            background-color: #E8D8C4;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .question-text {
            font-weight: 500;
            margin-bottom: 20px;
        }

        .scale-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .scale-label {
            width: 100px;
            text-align: center;
            font-size: 0.9rem;
        }

        .scale-options {
            display: flex;
            gap: 20px;
        }

        .scale-options input[type="radio"] {
            appearance: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            border: 2px solid #ccc;
            outline: none;
            cursor: pointer;
            transition: 0.2s;
        }

        .scale-options input[type="radio"]:checked {
            background-color: #6D2932;
            border-color: #6D2932;
        }

        .progress {
            height: 20px; /* Tingkatkan tinggi */
            border-radius: 10px; /* Opsional: membuat sudut menjadi melengkung */
            overflow: hidden; /* Pastikan sudut yang melengkung terlihat */
        }

        .progress-bar {
            height: 100%; /* Pastikan progress bar mengisi kontainer */
            border-radius: 10px; /* Opsional: sesuaikan dengan radius sudut */
        }
    </style>
</head>
<body class="<?= $themeClass; ?>">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#">
            <img src="logo.png" alt="Logo" width="40" height="40">
            <span class="ms-2">DSS-TI</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="kurikulum.php">Kurikulum</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link text-white" href="kriteria.php">Kriteria</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="alternatif.php">Alternatif</a></li>
    <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($userLoggedIn): ?>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="proses_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="Login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light me-2" href="Register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li class="nav-item">
                    <form method="POST" action="">
                        <button class="btn btn-outline-light" name="theme-toggle"><i class="<?= $themeIcon; ?>"></i></button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Kuis -->
<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-white text-center">
            <h5 class="fw-bold">KUIS</h5>
            <p>Isi beberapa pertanyaan tentang minat dan masukkan nilai mata kuliah Anda untuk mendapatkan rekomendasi konsentrasi yang sesuai.</p>
        </div>
<!-- Progress bar -->
<div class="progress mb-4">
    <div class="progress-bar custom-progress" id="quizProgressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>

<style>
    .custom-progress {
        background-color: #6D2932 !important;
    }
</style>

            <form action="input_nilai.php" method="POST">
                <?php
                $pertanyaan = [
                "Apakah Anda suka menganalisis data untuk mendukung keputusan bisnis?",
                "Seberapa tertarik Anda dalam membuat perangkat lunak berbasis database?",
                "Seberapa besar minat Anda dalam bidang Big Data dan analisis data besar?",
                "Seberapa antusias Anda dalam menerapkan teknik Data Mining?",
                "Apakah Anda berminat dalam pembuatan Data Warehouse untuk pengolahan data besar?",
                "Seberapa tertarik Anda mempelajari sistem pendukung keputusan (DSS)?",
                "Apakah Anda suka mengembangkan algoritma berbasis data?",
                "Seberapa besar ketertarikan Anda terhadap Machine Learning untuk analisis data?",
                "Seberapa tertarik Anda dengan pembuatan laporan analitik berbasis dashboard?",
                "Apakah Anda ingin bekerja di bidang Business Intelligence?",
                "Seberapa besar minat Anda dalam optimasi pengambilan keputusan berbasis data?",
                "Apakah Anda menyukai eksplorasi database dalam proyek nyata?",
                "Seberapa besar ketertarikan Anda pada teknik visualisasi data?",
                "Apakah Anda ingin berkarir sebagai Data Analyst atau Data Engineer?",
                "Seberapa berminat Anda mengembangkan solusi berbasis Artificial Intelligence untuk bisnis?"
                ];

                foreach ($pertanyaan as $index => $soal) {
                    echo "<div class='question-card'>";
                    echo "<p class='question-text'>$soal</p>";
                    echo "<div class='scale-container'>";
                    echo "<div class='scale-label'>Sangat Setuju</div>";
                    echo "<div class='scale-options'>";
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<input type='radio' class='quiz-input' name='q$index' value='$i' required>";
                    }
                    echo "</div>";
                    echo "<div class='scale-label'>Sangat Tidak Setuju</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
                <div class="text-end mt-4">
                    <button type="submit" class="btn custom-submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS + Progress Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.quiz-input');
        const progressBar = document.getElementById('quizProgressBar');
        const questionCount = inputs.length / 5; // Setiap pertanyaan memiliki 5 input radio
        const answered = new Set();

        inputs.forEach(input => {
            input.addEventListener('change', () => {
                answered.add(input.name);
                const progress = (answered.size / questionCount) * 100;
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
                progressBar.textContent = Math.round(progress) + '%';
            });
        });
    });
</script>
</body>
</html>
