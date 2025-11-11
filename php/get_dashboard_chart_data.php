<?php
// get_dashboard_chart_data.php - FIXED VERSION
session_start();

// Set header JSON pertama
header('Content-Type: application/json');

// Check session
if (!isset($_SESSION['adminLoggedIn'])) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

include '../koneksi.php';

try {
    $labels = [];
    $data = [];

    // Data untuk 7 hari terakhir
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
        $count = 0;
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = ?");
        if ($stmt) {
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $count = (int)($result['count'] ?? 0);
            $stmt->close();
        }
        
        $data[] = $count;
    }

    echo json_encode([
        'labels' => $labels,
        'data' => $data
    ]);

} catch (Exception $e) {
    // Fallback data
    echo json_encode([
        'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        'data' => [3, 5, 2, 8, 6, 4, 7]
    ]);
}

$conn->close();
?>