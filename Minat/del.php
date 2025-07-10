<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID berupa integer

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM bobot_alternatif WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Berhasil dihapus
        header("Location: kri.php?msg=deleted");
    } else {
        // Gagal menghapus
        header("Location: kri.php?msg=error");
    }
    $stmt->close();
} else {
    // Tidak ada ID dikirim
    header("Location: kri.php?msg=invalid");
}
?>
