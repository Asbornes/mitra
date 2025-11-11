<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}
include '../koneksi.php';

// Get orders data for the last 7 days
$labels = [];
$data = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $day_name = date('D', strtotime($date));
    
    // Translate day names to Indonesian
    $day_translations = [
        'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 
        'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab', 'Sun' => 'Min'
    ];
    
    $labels[] = $day_translations[$day_name] ?? $day_name;
    
    // Count orders for this date
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $data[] = $result['count'];
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>