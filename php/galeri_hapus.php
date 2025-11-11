<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil nama file dulu untuk hapus dari folder uploads
    $getFoto = $conn->prepare("SELECT foto FROM galeri WHERE id = ?");
    $getFoto->bind_param("i", $id);
    $getFoto->execute();
    $result = $getFoto->get_result();
    if ($row = $result->fetch_assoc()) {
        $filePath = "../uploads/" . $row['foto'];
        if (file_exists($filePath) && $row['foto'] != '') {
            unlink($filePath); // hapus file fisik
        }
    }
    $getFoto->close();

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Data galeri berhasil dihapus.";
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "ID tidak valid!";
}

$conn->close();
?>