<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';
?>
<?php
include '../koneksi.php';

// Pastikan ID dikirim dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Gunakan prepared statement
    $stmt = $conn->prepare("DELETE FROM harga WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Kembali ke halaman admin bagian harga
header("Location: ../admin.php#harga");
exit;
?>
s