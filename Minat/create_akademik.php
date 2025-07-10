<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $_SESSION['message'] = "Silakan login untuk mengakses fitur ini.";
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "spk_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_mk = htmlspecialchars(trim($_POST['kode_mk']));
    $mata_kuliah = htmlspecialchars(trim($_POST['mata_kuliah']));
    $sks = htmlspecialchars(trim($_POST['sks']));
    $semester = htmlspecialchars(trim($_POST['semester']));
    $status = htmlspecialchars(trim($_POST['status']));

    // Jika status wajib, peminatan = "-"
    if ($status == "Wajib") {
        $peminatan = "-";
    } elseif ($status == "Pilihan") {
        $peminatan = htmlspecialchars(trim($_POST['peminatan']));
    } else {
        $peminatan = "-";
    }

    $stmt = $conn->prepare("INSERT INTO akademik (kode_mk, mata_kuliah, sks, peminatan, semester, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $kode_mk, $mata_kuliah, $sks, $peminatan, $semester, $status);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil ditambahkan.";
        header("Location: akademik.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8" />
    <title>Tambah Data Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    .container {
        max-width: 600px; /* batasi lebar container */
    }
    .form-label {
        font-weight: 600;
    }
    .bg-maroon {
        background-color: maroon !important;
    }
    </style>

    <script>
        function onStatusChange() {
            const status = document.getElementById("status").value;
            const peminatanSelect = document.getElementById("peminatan");
            const peminatanGroup = document.getElementById("peminatan-group");

            if (status === "Pilihan") {
                peminatanSelect.disabled = false;
                peminatanGroup.style.display = "flex";
            } else {
                peminatanSelect.value = "";
                peminatanSelect.disabled = true;
                peminatanGroup.style.display = "none";
            }
        }

        window.addEventListener("DOMContentLoaded", () => {
            onStatusChange();
            document.getElementById("status").addEventListener("change", onStatusChange);
        });
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-maroon text-white">
                <h4 class="mb-0">Tambah Mata Kuliah</h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3 align-items-center">
                        <label for="kode_mk" class="col-sm-4 col-form-label">Kode Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <input type="text" name="kode_mk" id="kode_mk" class="form-control" required />
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="mata_kuliah" class="col-sm-4 col-form-label">Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <input type="text" name="mata_kuliah" id="mata_kuliah" class="form-control" required />
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="sks" class="col-sm-4 col-form-label">SKS:</label>
                        <div class="col-sm-8">
                            <input type="text" name="sks" id="sks" class="form-control" required />
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label for="semester" class="col-sm-4 col-form-label">Semester:</label>
                        <div class="col-sm-8">
                            <input type="text" name="semester" id="semester" class="form-control" required />
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <label for="status" class="col-sm-4 col-form-label">Status Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-select" required>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center" id="peminatan-group" style="display:none;">
                        <label for="peminatan" class="col-sm-4 col-form-label">Peminatan:</label>
                        <div class="col-sm-8">
                            <select name="peminatan" id="peminatan" class="form-select">
                                <option value="">-- Pilih Peminatan --</option>
                                <option value="RPL">RPL</option>
                                <option value="Data">Data</option>
                                <option value="Jaringan">Jaringan</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="<?php echo htmlspecialchars($previous_page); ?>" class="btn btn-secondary">← Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
