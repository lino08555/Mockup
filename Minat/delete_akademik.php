<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "spk_db";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM akademik WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: akademik.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }
}
?>