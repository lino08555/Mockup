<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "spk_db";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM akademik WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='matakuliah.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='matakuliah.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $kode_mk = $_POST['kode_mk'];
    $mata_kuliah = $_POST['mata_kuliah'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];
    $status = $_POST['status'];
    $peminatan = isset($_POST['peminatan']) ? $_POST['peminatan'] : '-';

    if ($status === "Wajib") {
        $peminatan = "-";
    }

    $sql = "UPDATE akademik SET kode_mk=?, mata_kuliah=?, sks=?, semester=?, status=?, peminatan=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $kode_mk, $mata_kuliah, $sks, $semester, $status, $peminatan, $id);
    if ($stmt->execute()) {
        header("Location: matakuliah.php");
        exit();
    } else {
        echo "Gagal mengupdate data: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="logo.png" type="image/x-icon">
    <meta charset="UTF-8" />
    <title>Edit Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .container { max-width: 600px; }
        .bg-maroon { background-color: maroon !important; }
    </style>
    <script>
        function onStatusChange() {
            const status = document.getElementById("status").value;
            const peminatanGroup = document.getElementById("peminatan-group");
            const peminatanSelect = document.getElementById("peminatan");

            if (status === "Pilihan") {
                peminatanGroup.style.display = "flex";
                peminatanSelect.disabled = false;
            } else {
                peminatanSelect.value = "";
                peminatanGroup.style.display = "none";
                peminatanSelect.disabled = true;
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
                <h4 class="mb-0">Edit Mata Kuliah</h4>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>" />

                    <!-- Kode Mata Kuliah -->
                    <div class="row mb-3 align-items-center">
                        <label for="kode_mk" class="col-sm-4 col-form-label">Kode Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <input type="text" name="kode_mk" id="kode_mk" class="form-control" 
                                   value="<?php echo htmlspecialchars($row['kode_mk']); ?>" required />
                        </div>
                    </div>

                    <!-- Mata Kuliah -->
                    <div class="row mb-3 align-items-center">
                        <label for="mata_kuliah" class="col-sm-4 col-form-label">Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <input type="text" name="mata_kuliah" id="mata_kuliah" class="form-control" 
                                   value="<?php echo htmlspecialchars($row['mata_kuliah']); ?>" required />
                        </div>
                    </div>

                    <!-- SKS -->
                    <div class="row mb-3 align-items-center">
                        <label for="sks" class="col-sm-4 col-form-label">SKS:</label>
                        <div class="col-sm-8">
                            <input type="text" name="sks" id="sks" class="form-control" 
                                   value="<?php echo htmlspecialchars($row['sks']); ?>" required />
                        </div>
                    </div>

                    <!-- Semester -->
                    <div class="row mb-3 align-items-center">
                        <label for="semester" class="col-sm-4 col-form-label">Semester:</label>
                        <div class="col-sm-8">
                            <input type="text" name="semester" id="semester" class="form-control" 
                                   value="<?php echo htmlspecialchars($row['semester']); ?>" required />
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3 align-items-center">
                        <label for="status" class="col-sm-4 col-form-label">Status Mata Kuliah:</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-select" required>
                                <option value="Wajib" <?php echo ($row['status'] === "Wajib") ? "selected" : ""; ?>>Wajib</option>
                                <option value="Pilihan" <?php echo ($row['status'] === "Pilihan") ? "selected" : ""; ?>>Pilihan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Peminatan -->
                    <div class="row mb-3 align-items-center" id="peminatan-group" style="display:none;">
                        <label for="peminatan" class="col-sm-4 col-form-label">Peminatan:</label>
                        <div class="col-sm-8">
                            <select name="peminatan" id="peminatan" class="form-select">
                                <option value="">-- Pilih Peminatan --</option>
                                <option value="RPL" <?php echo ($row['peminatan'] === "RPL") ? "selected" : ""; ?>>RPL</option>
                                <option value="Data" <?php echo ($row['peminatan'] === "Data") ? "selected" : ""; ?>>Data</option>
                                <option value="Jaringan" <?php echo ($row['peminatan'] === "Jaringan") ? "selected" : ""; ?>>Jaringan</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="matakuliah.php" class="btn btn-secondary">← Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
