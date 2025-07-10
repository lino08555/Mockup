<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$id_alternatif = $_POST['id_alternatif'];
$nilai = $_POST['nilai'];

// Simpan mahasiswa
mysqli_query($conn, "INSERT INTO mahasiswa (nama) VALUES ('$nama')");
$id_mahasiswa = mysqli_insert_id($conn);

// Simpan nilai per kriteria
foreach ($nilai as $id_kriteria => $v) {
    mysqli_query($conn, "INSERT INTO nilai (id_mahasiswa, id_alternatif, id_kriteria, nilai)
                         VALUES ('$id_mahasiswa', '$id_alternatif', '$id_kriteria', '$v')");
}

header("Location: hasil_saw.php?id_mahasiswa=$id_mahasiswa");