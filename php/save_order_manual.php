<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}

include '../koneksi.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get form data dengan default values
$edit_order_id = $_POST['order_id'] ?? '';
$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name'] ?? 'Customer');
$customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone'] ?? '081234567890');
$customer_address = mysqli_real_escape_string($conn, $_POST['customer_address'] ?? 'Address');
$address_notes = mysqli_real_escape_string($conn, $_POST['address_notes'] ?? '');
$service_name = mysqli_real_escape_string($conn, $_POST['service_name'] ?? 'Layanan Reguler');
$total_amount = intval($_POST['total_amount'] ?? 0);
$pickup_date = $_POST['pickup_date'] ?? date('Y-m-d');
$pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time'] ?? '10:00-12:00');
$payment_method = $_POST['payment_method'] ?? 'cash';
$status = $_POST['status'] ?? 'pending';
$notes = mysqli_real_escape_string($conn, $_POST['notes'] ?? '');

// Validation
if (empty($customer_name) || empty($customer_phone) || empty($customer_address) || empty($service_name)) {
    die("Data tidak lengkap! Harap isi semua field yang wajib.");
}

if ($total_amount <= 0) {
    die("Total amount harus lebih dari 0!");
}

try {
    if ($edit_order_id) {
        // UPDATE existing order
        $stmt = $conn->prepare("UPDATE orders SET 
            customer_name = ?, 
            customer_phone = ?, 
            customer_address = ?, 
            address_notes = ?,
            service_name = ?,
            total_amount = ?,
            pickup_date = ?,
            pickup_time = ?,
            payment_method = ?,
            status = ?,
            notes = ?,
            updated_at = NOW()
            WHERE order_id = ?");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sssssissssss", 
            $customer_name, $customer_phone, $customer_address, $address_notes,
            $service_name, $total_amount, $pickup_date, $pickup_time,
            $payment_method, $status, $notes, $edit_order_id
        );
    } else {
        // INSERT new order
        $order_id = 'ORD' . date('YmdHis') . rand(100, 999);
        $quantity = 1.0;
        $unit_price = $total_amount;
        
        $stmt = $conn->prepare("INSERT INTO orders (
            order_id, customer_name, customer_phone, customer_address, address_notes,
            service_name, quantity, unit_price, total_amount, delivery_cost,
            payment_method, pickup_date, pickup_time, status, notes, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, NOW())");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssssdiisssss", 
            $order_id, $customer_name, $customer_phone, $customer_address, $address_notes,
            $service_name, $quantity, $unit_price, $total_amount,
            $payment_method, $pickup_date, $pickup_time, $status, $notes
        );
    }

    if ($stmt->execute()) {
        if (!$edit_order_id) {
            // Also save to order_items
            $new_order_id = $order_id;
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, service_name, quantity, unit_price, total_price, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            if ($item_stmt) {
                $item_stmt->bind_param("ssdii", $new_order_id, $service_name, $quantity, $unit_price, $total_amount);
                $item_stmt->execute();
                $item_stmt->close();
            }
        }
        
        echo "Data pesanan berhasil " . ($edit_order_id ? "diperbarui" : "disimpan");
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>