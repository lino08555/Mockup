<?php
session_start();
session_unset(); // Menghapus semua data sesi
session_destroy(); // Menghancurkan sesi
header('Location: index.php'); // Redirect setelah logout
exit();
?>
