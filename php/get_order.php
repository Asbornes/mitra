<?php
// get_order.php - FIXED VERSION
session_start();

// Set header JSON pertama
header('Content-Type: application/json');

// Check session
if (!isset($_SESSION['adminLoggedIn'])) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Include koneksi
include '../koneksi.php';

$order_id = $_GET['order_id'] ?? '';

if (empty($order_id)) {
    echo json_encode(['error' => 'Order ID is required']);
    exit;
}

try {
    // Query untuk mendapatkan data order
    $stmt = $conn->prepare("
        SELECT 
            order_id, customer_name, customer_phone, customer_address, 
            address_notes, service_name, total_amount, pickup_date, 
            pickup_time, payment_method, status, notes
        FROM orders 
        WHERE order_id = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Prepare statement failed");
    }
    
    $stmt->bind_param("s", $order_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed");
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Format response
        $response = [
            'order_id' => $order['order_id'],
            'customer_name' => $order['customer_name'] ?? '',
            'customer_phone' => $order['customer_phone'] ?? '',
            'customer_address' => $order['customer_address'] ?? '',
            'address_notes' => $order['address_notes'] ?? '',
            'service_name' => $order['service_name'] ?? '',
            'total_amount' => $order['total_amount'] ?? 0,
            'pickup_date' => $order['pickup_date'] ?? '',
            'pickup_time' => $order['pickup_time'] ?? '',
            'payment_method' => $order['payment_method'] ?? 'cash',
            'status' => $order['status'] ?? 'pending',
            'notes' => $order['notes'] ?? ''
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Order not found']);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}

$conn->close();
?>