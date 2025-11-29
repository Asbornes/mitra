<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$order_id = $_GET['order_id'] ?? '';
$status = $_GET['status'] ?? '';

if ($order_id && in_array($status, ['pending', 'process', 'completed', 'cancelled'])) {
    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE order_id = ?");
    $stmt->bind_param("ss", $status, $order_id);
    
    if ($stmt->execute()) {
        echo "Status pesanan berhasil diperbarui.";
    } else {
        echo "Gagal memperbarui status: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "Data tidak valid!";
}

$conn->close();
?>