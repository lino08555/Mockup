<?php
include 'koneksi.php';

// Sinkronisasi data kriteria ke tabel matkul
$q_kriteria = mysqli_query($conn, "SELECT * FROM kriteria");
while ($k = mysqli_fetch_assoc($q_kriteria)) {
    $kode = mysqli_real_escape_string($conn, $k['kode']);
    $nama = mysqli_real_escape_string($conn, $k['nama_kriteria']);

    // Cek apakah sudah ada di tabel matkul
    $cek = mysqli_query($conn, "SELECT COUNT(*) as jml FROM matkul WHERE kode = '$kode'");
    $cekRow = mysqli_fetch_assoc($cek);
    if ($cekRow['jml'] == 0) {
        // Jika belum ada, masukkan ke matkul
        mysqli_query($conn, "INSERT INTO matkul (kode, nama) VALUES ('$kode', '$nama')");
    }
}

// Hitung jumlah alternatif
$altResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alternatif");
$altRow = mysqli_fetch_assoc($altResult);
$jumlah_alternatif = $altRow['total'];

// Jika alternatif > 3, tambahkan 5 pertanyaan dummy ke DB jika belum ada
if ($jumlah_alternatif > 3) {
    // Cek apakah dummy sudah ada
    $cek_dummy = mysqli_query($conn, "SELECT COUNT(*) AS jml FROM pertanyaan WHERE is_dummy = 1");
    $dummy_row = mysqli_fetch_assoc($cek_dummy);
    if ($dummy_row['jml'] < 5) {
        for ($i = 1; $i <= 5; $i++) {
            $dummy_teks = "Pertanyaan Tambahan untuk Alternatif {$jumlah_alternatif} - {$i}";
            mysqli_query($conn, "INSERT INTO pertanyaan (teks, is_dummy) VALUES ('$dummy_teks', 1)");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis Minat dan Nilai</title>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #fef0f4, #fde2e4);
            color: #333;
        }
        .nilai-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
    margin-top: 20px;
}

.nilai-card {
    background-color: #fce4ec;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
}

.nilai-card label {
    font-weight: 600;
    color: #6d2c32;
    margin-bottom: 10px;
    font-size: 14px;
}

.nilai-card input[type="number"] {
    padding: 10px;
    border: 2px solid #ffc1e3;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s ease;
}

.nilai-card input[type="number"]:focus {
    outline: none;
    border-color: #b56576;
    background-color: #fff;
}


        .container {
            max-width: 950px;
            margin: 40px auto;
            padding: 40px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #6d2c32;
            margin-bottom: 20px;
        }

        .progress-container {
            margin-bottom: 30px;
        }

        .progress-bar-custom {
            background-color: #6d2c32;
            color: white;
            text-align: center;
            border-radius: 10px;
            font-weight: bold;
            padding: 4px 0;
            width: 7%; /* Ganti sesuai progress */
        }

        .card-info {
            background-color: #fff0f5;
            border-left: 8px solid #ff4d6d;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        table.likert-scale {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.likert-scale th, table.likert-scale td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table.likert-scale th {
            background-color: #fbd4de;
            color: #333;
        }

        h3 {
            color: #b56576;
            margin-top: 40px;
            border-bottom: 2px solid #b56576;
            padding-bottom: 8px;
        }

        label {
            font-weight: 500;
            display: block;
            margin-top: 20px;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-top: 5px;
            font-size: 14px;
        }

        input[type="number"]:focus {
            border-color: #b56576;
        }

        .quiz-card {
            background-color: #ead4c0;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .quiz-card p {
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .radio-scale {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding: 0 20px;
        }

        .radio-scale span {
            font-size: 13px;
            color: #555;
        }

        .radio-scale input[type="radio"] {
            display: none;
        }

        .radio-scale label {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #eee;
            display: inline-block;
            line-height: 30px;
            text-align: center;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .radio-scale input[type="radio"]:checked + label {
            background-color: #6d2c32;
            color: white;
            font-weight: bold;
        }

        button {
            margin-top: 40px;
            width: 100%;
            padding: 14px;
            background-color: #6d2c32;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #4e1f25;
        }

        @media (max-width: 768px) {
            .radio-scale {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>KUIS</h2>
    <p style="text-align:center;">Isi beberapa pertanyaan tentang minat dan masukkan nilai mata kuliah Anda untuk mendapatkan rekomendasi konsentrasi yang sesuai.</p>
  

    <!-- Petunjuk -->
    <div class="card-info">
        <h4 style="margin-top:0;color:#ff4d6d;">📝 Petunjuk Pengisian</h4>
        <ul style="line-height:1.8;margin-left:20px;">
            <li>Masukkan nilai semua mata kuliah dengan skala 0–100.</li>
            <li>Jawab kuis minat dengan memilih angka 1 sampai 5 sesuai pendapat Anda.</li>
        </ul>
        <table class="likert-scale">
            <tr><th>Skor</th><th>Makna</th></tr>
            <tr><td>1</td><td>😠 Sangat Tidak Setuju</td></tr>
            <tr><td>2</td><td>🙁 Tidak Setuju</td></tr>
            <tr><td>3</td><td>😐 Netral</td></tr>
            <tr><td>4</td><td>🙂 Setuju</td></tr>
            <tr><td>5</td><td>😄 Sangat Setuju</td></tr>
        </table>
    </div>

    <form action="proses_input.php" method="POST">

                <h3>2. Kuis Minat (Skala Likert 1 – 5)</h3>
        <?php
        $q = mysqli_query($conn, "SELECT * FROM pertanyaan ORDER BY id_pertanyaan");
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<div class='quiz-card'>";
            echo "<p>{$row['teks']}</p>";
            echo "<div class='radio-scale'>";
            echo "<span>Sangat Tidak Setuju</span>";
            for ($i = 1; $i <= 5; $i++) {
                echo "
                <div>
                    <input type='radio' id='q{$row['id_pertanyaan']}_$i' name='kuis[{$row['id_pertanyaan']}]' value='$i' required>
                    <label for='q{$row['id_pertanyaan']}_$i'></label>
                </div>";
            }
            echo "<span>Sangat Setuju</span>";
            echo "</div></div>";
        }
        ?>
        <h3>1. Nilai Mata Kuliah</h3>
        <div class="nilai-wrapper">
        <?php
        $q = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");
        while ($row = mysqli_fetch_assoc($q)) {
            echo "
            <div class='nilai-card'>
                <label for='kriteria{$row['id_kriteria']}'>{$row['nama_kriteria']}</label>
                <input type='number' name='kriteria[{$row['id_kriteria']}]' id='kriteria{$row['id_kriteria']}' min='0' max='100' required>
            </div>
            ";
        }
        ?>
        </div>



        <button type="submit">Kirim Jawaban</button>
    </form>
</div>
</body>
</html>
