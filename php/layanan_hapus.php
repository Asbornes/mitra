<?php
include '../koneksi.php';

// Pastikan ID ada dan berupa angka
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus data dengan prepared statement (lebih aman)
    $stmt = $conn->prepare("DELETE FROM layanan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Setelah hapus, arahkan kembali ke halaman admin bagian layanan
header("Location: ../admin.php#layanan");
exit;
?>
