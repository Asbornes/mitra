<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM delivery_rates WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Data biaya antar jemput berhasil dihapus.";
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "ID tidak valid!";
}

$conn->close();
?>