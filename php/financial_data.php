<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Access denied']);
    exit;
}

include '../koneksi.php';

// Set header JSON
header('Content-Type: application/json');

function getFinancialData($conn, $year, $month) {
    $response = [
        'monthly_revenue' => [],
        'service_performance' => [],
        'payment_methods' => [],
        'summary' => [
            'total_revenue' => 0,
            'total_orders' => 0,
            'average_order' => 0,
            'growth_rate' => 0
        ]
    ];

    try {
        // 1. DATA PENDAPATAN TAHUNAN (12 bulan terakhir)
        $monthly_revenue = [];
        $current_year = date('Y');
        
        for ($m = 1; $m <= 12; $m++) {
            $stmt = $conn->prepare("
                SELECT 
                    COALESCE(SUM(total_amount), 0) as revenue,
                    COALESCE(COUNT(*), 0) as orders
                FROM orders 
                WHERE YEAR(created_at) = ? AND MONTH(created_at) = ? AND status = 'completed'
            ");
            
            if ($stmt) {
                $stmt->bind_param("ii", $current_year, $m);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                
                $monthly_revenue[] = [
                    'month' => $m,
                    'revenue' => (int)($data['revenue'] ?? 0),
                    'orders' => (int)($data['orders'] ?? 0)
                ];
                
                $stmt->close();
            }
        }
        $response['monthly_revenue'] = $monthly_revenue;

        // 2. PERFORMANCE LAYANAN (dinamis berdasarkan data aktual)
        $service_performance = [];
        $service_query = $conn->query("
            SELECT 
                service_name,
                COUNT(*) as order_count,
                COALESCE(SUM(total_amount), 0) as total_revenue
            FROM orders 
            WHERE status = 'completed'
            GROUP BY service_name
            ORDER BY total_revenue DESC
            LIMIT 10
        ");
        
        if ($service_query) {
            while ($row = $service_query->fetch_assoc()) {
                $service_performance[] = [
                    'service_name' => $row['service_name'],
                    'order_count' => (int)$row['order_count'],
                    'total_revenue' => (int)$row['total_revenue']
                ];
            }
        }
        $response['service_performance'] = $service_performance;

        // 3. METODE PEMBAYARAN (dinamis)
        $payment_methods = [];
        $payment_query = $conn->query("
            SELECT 
                payment_method as method,
                COUNT(*) as count,
                COALESCE(SUM(total_amount), 0) as total
            FROM orders 
            WHERE status = 'completed'
            GROUP BY payment_method
        ");
        
        if ($payment_query) {
            while ($row = $payment_query->fetch_assoc()) {
                $payment_methods[] = [
                    'method' => $row['method'],
                    'count' => (int)$row['count'],
                    'total' => (int)$row['total']
                ];
            }
        }
        $response['payment_methods'] = $payment_methods;

        // 4. SUMMARY BULAN INI (dinamis)
        $current_month = date('m');
        $current_year = date('Y');
        
        $summary_query = $conn->prepare("
            SELECT 
                COALESCE(COUNT(*), 0) as total_orders,
                COALESCE(SUM(total_amount), 0) as total_revenue,
                COALESCE(AVG(total_amount), 0) as average_order
            FROM orders 
            WHERE status = 'completed' AND YEAR(created_at) = ? AND MONTH(created_at) = ?
        ");
        
        if ($summary_query) {
            $summary_query->bind_param("ii", $current_year, $current_month);
            $summary_query->execute();
            $summary_result = $summary_query->get_result();
            $current_data = $summary_result->fetch_assoc();
            
            $current_revenue = (int)($current_data['total_revenue'] ?? 0);
            $current_orders = (int)($current_data['total_orders'] ?? 0);
            $current_avg = (int)round($current_data['average_order'] ?? 0);

            // Data bulan sebelumnya untuk growth rate
            $prev_month = $current_month - 1;
            $prev_year = $current_year;
            if ($prev_month == 0) {
                $prev_month = 12;
                $prev_year = $current_year - 1;
            }

            $prev_query = $conn->prepare("
                SELECT 
                    COALESCE(COUNT(*), 0) as total_orders,
                    COALESCE(SUM(total_amount), 0) as total_revenue
                FROM orders 
                WHERE status = 'completed' AND YEAR(created_at) = ? AND MONTH(created_at) = ?
            ");
            
            if ($prev_query) {
                $prev_query->bind_param("ii", $prev_year, $prev_month);
                $prev_query->execute();
                $prev_result = $prev_query->get_result();
                $prev_data = $prev_result->fetch_assoc();
                
                $prev_revenue = (int)($prev_data['total_revenue'] ?? 0);
                $prev_orders = (int)($prev_data['total_orders'] ?? 0);

                // Calculate growth rate
                $growth_rate = 0;
                if ($prev_revenue > 0) {
                    $growth_rate = (($current_revenue - $prev_revenue) / $prev_revenue) * 100;
                } elseif ($current_revenue > 0) {
                    $growth_rate = 100; // First month with revenue
                }

                $response['summary'] = [
                    'total_revenue' => $current_revenue,
                    'total_orders' => $current_orders,
                    'average_order' => $current_avg,
                    'growth_rate' => round($growth_rate, 2)
                ];
                
                $prev_query->close();
            }
            $summary_query->close();
        }

        return $response;

    } catch (Exception $e) {
        error_log("Financial data error: " . $e->getMessage());
        return $response; // Return empty structure
    }
}

// Main execution
try {
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    $financial_data = getFinancialData($conn, $year, $month);
    echo json_encode($financial_data);

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'monthly_revenue' => [],
        'service_performance' => [],
        'payment_methods' => [],
        'summary' => [
            'total_revenue' => 0,
            'total_orders' => 0,
            'average_order' => 0,
            'growth_rate' => 0
        ]
    ]);
}

$conn->close();
?>