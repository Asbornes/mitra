<?php
// save_order.php - FIXED PAYMENT METHOD VERSION
session_start();

// Set header JSON pertama kali
header('Content-Type: application/json');

// Include koneksi
include '../koneksi.php';

// Function untuk response JSON
function sendJsonResponse($success, $message, $additionalData = []) {
    $response = ['success' => $success, 'message' => $message] + $additionalData;
    echo json_encode($response);
    exit;
}

// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Cek method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(false, 'Invalid request method');
    }

    // Get raw input
    $input = file_get_contents('php://input');
    
    if (empty($input)) {
        sendJsonResponse(false, 'No data received');
    }

    // Decode JSON
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendJsonResponse(false, 'Invalid JSON: ' . json_last_error_msg());
    }

    // Validasi data required
    $required_fields = ['first_name', 'phone', 'full_address', 'services', 'pickup_date', 'pickup_time'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            sendJsonResponse(false, "Field $field harus diisi.");
        }
    }

    if (empty($data['services']) || !is_array($data['services']) || count($data['services']) === 0) {
        sendJsonResponse(false, 'Pilih minimal satu layanan.');
    }

    // Generate order ID
    $order_id = 'ORD' . date('YmdHis') . rand(100, 999);
    
    // Process services
    $serviceNames = [];
    $total_quantity = 0;
    $subtotal = 0;
    $validServices = [];

    foreach ($data['services'] as $service) {
        if (!empty($service['name']) && !empty($service['quantity']) && !empty($service['price'])) {
            $quantity = floatval($service['quantity']);
            $price = floatval($service['price']);
            
            if ($quantity > 0 && $price >= 0) {
                $serviceNames[] = $service['name'] . ' (' . $quantity . ($service['unit'] ?? 'kg') . ')';
                $total_quantity += $quantity;
                $subtotal += $price * $quantity;
                $validServices[] = $service;
            }
        }
    }

    if (count($validServices) === 0) {
        sendJsonResponse(false, 'Tidak ada layanan yang valid.');
    }

    $serviceName = implode(', ', $serviceNames);
    $unitPrice = $total_quantity > 0 ? round($subtotal / $total_quantity) : 0;

    // Prepare data dengan default values
    $customer_name = trim(($data['first_name'] ?? 'Customer') . ' ' . ($data['last_name'] ?? ''));
    $phone = $data['phone'] ?? '081234567890';
    $address = $data['full_address'] ?? 'Alamat tidak diisi';
    $address_notes = $data['address_notes'] ?? '';
    
    // FIX: Mapping payment method ke ENUM yang valid
    $payment_method_input = strtolower($data['payment_method'] ?? 'cash');
    
    // Hanya allow 'cash' dan 'transfer'
    if ($payment_method_input === 'cod') {
        $payment_method = 'cash'; // COD dianggap sebagai cash
    } elseif ($payment_method_input === 'transfer') {
        $payment_method = 'transfer';
    } else {
        $payment_method = 'cash'; // default ke cash
    }
    
    $pickup_date = $data['pickup_date'] ?? date('Y-m-d');
    $pickup_time = $data['pickup_time'] ?? '10:00-12:00';
    $status = 'pending';

    // Debug data sebelum insert
    error_log("Payment Method Mapping: {$payment_method_input} -> {$payment_method}");

    // Insert ke orders table
    $stmt = $conn->prepare("
        INSERT INTO orders (
            order_id, customer_name, customer_phone, customer_address, address_notes,
            service_name, quantity, unit_price, total_amount, delivery_cost,
            payment_method, pickup_date, pickup_time, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, NOW())
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $delivery_cost = 0;
    $stmt->bind_param(
        "ssssssdiissss", 
        $order_id,
        $customer_name,
        $phone,
        $address,
        $address_notes,
        $serviceName,
        $total_quantity,
        $unitPrice,
        $subtotal,
        $payment_method,
        $pickup_date,
        $pickup_time,
        $status
    );

    if ($stmt->execute()) {
        // Insert order items
        foreach ($validServices as $service) {
            $item_stmt = $conn->prepare("
                INSERT INTO order_items (order_id, service_name, quantity, unit_price, total_price, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            if ($item_stmt) {
                $service_price = floatval($service['price']);
                $service_quantity = floatval($service['quantity']);
                $total_price = $service_price * $service_quantity;
                
                $item_stmt->bind_param(
                    "ssdii", 
                    $order_id, 
                    $service['name'], 
                    $service_quantity, 
                    $service_price, 
                    $total_price
                );
                
                if (!$item_stmt->execute()) {
                    error_log("Error inserting order item: " . $item_stmt->error);
                }
                
                $item_stmt->close();
            }
        }

        $stmt->close();
        
        sendJsonResponse(true, 'Pesanan berhasil disimpan!', [
            'order_id' => $order_id,
            'customer_name' => $customer_name,
            'total_amount' => $subtotal
        ]);

    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

} catch (Exception $e) {
    error_log("Save order error: " . $e->getMessage());
    sendJsonResponse(false, 'System error: ' . $e->getMessage());
}

$conn->close();
?>