<?php
include '../koneksi.php';

header('Content-Type: application/json');

try {
    $result = $conn->query("SELECT * FROM delivery_rates ORDER BY range_min");
    $rates = [];
    
    while ($row = $result->fetch_assoc()) {
        $rates[] = $row;
    }
    
    echo json_encode($rates);
} catch (Exception $e) {
    echo json_encode([]);
}

$conn->close();
?>