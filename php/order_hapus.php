<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$order_id = $_GET['order_id'] ?? '';

if ($order_id) {
    // First delete from order_items
    $delete_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $delete_items->bind_param("s", $order_id);
    $delete_items->execute();
    $delete_items->close();
    
    // Then delete from orders
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    
    if ($stmt->execute()) {
        echo "Data pesanan berhasil dihapus.";
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "ID pesanan tidak valid!";
}

$conn->close();
?>