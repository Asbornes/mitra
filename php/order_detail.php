<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$order_id = $_GET['order_id'] ?? '';

if ($order_id) {
    // Get order details
    $order_stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $order_stmt->bind_param("s", $order_id);
    $order_stmt->execute();
    $order = $order_stmt->get_result()->fetch_assoc();
    $order_stmt->close();
    
    if ($order) {
        // Get order items
        $items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $items_stmt->bind_param("s", $order_id);
        $items_stmt->execute();
        $items = $items_stmt->get_result();
        
        echo '
        <div class="order-detail">
            <div class="order-header">
                <h4>Detail Pesanan #' . $order['order_id'] . '</h4>
                <span class="status-badge ' . $order['status'] . '">' . $order['status'] . '</span>
            </div>
            
            <div class="order-info-grid">
                <div class="info-section">
                    <h5>Informasi Pelanggan</h5>
                    <div class="info-item">
                        <label>Nama:</label>
                        <span>' . $order['customer_name'] . '</span>
                    </div>
                    <div class="info-item">
                        <label>WhatsApp:</label>
                        <span>' . $order['customer_phone'] . '</span>
                    </div>
                    <div class="info-item">
                        <label>Alamat:</label>
                        <span>' . $order['customer_address'] . '</span>
                    </div>';
        
        if (!empty($order['address_notes'])) {
            echo '<div class="info-item">
                    <label>Keterangan Alamat:</label>
                    <span>' . $order['address_notes'] . '</span>
                  </div>';
        }
        
        echo '</div>
                
                <div class="info-section">
                    <h5>Informasi Penjemputan</h5>
                    <div class="info-item">
                        <label>Tanggal:</label>
                        <span>' . date('d M Y', strtotime($order['pickup_date'])) . '</span>
                    </div>
                    <div class="info-item">
                        <label>Waktu:</label>
                        <span>' . $order['pickup_time'] . '</span>
                    </div>
                    <div class="info-item">
                        <label>Metode Bayar:</label>
                        <span>' . ($order['payment_method'] == 'cod' ? 'Cash on Delivery' : 'Transfer Bank') . '</span>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h5>Detail Layanan</h5>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Quantity</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        $total_amount = 0;
        while ($item = $items->fetch_assoc()) {
            $total_amount += $item['total_price'];
            echo '<tr>
                    <td>' . $item['service_name'] . '</td>
                    <td>' . $item['quantity'] . '</td>
                    <td>Rp ' . number_format($item['unit_price']) . '</td>
                    <td>Rp ' . number_format($item['total_price']) . '</td>
                  </tr>';
        }
        
        echo '</tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Total:</td>
                            <td style="font-weight: bold;">Rp ' . number_format($total_amount) . '</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="order-notes">
                <h5>Catatan</h5>';
        
        if (!empty($order['notes'])) {
            echo '<p>' . $order['notes'] . '</p>';
        } else {
            echo '<p>Tidak ada catatan</p>';
        }
        
        echo '</div>
        </div>
        
        <style>
        .order-detail {
            padding: 1rem;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-3);
        }
        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .info-section {
            background: var(--light);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
        }
        .info-section h5 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--light-3);
        }
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-item label {
            font-weight: 600;
            color: var(--dark);
        }
        .order-items {
            margin-bottom: 2rem;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--light);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .items-table th,
        .items-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light-3);
        }
        .items-table th {
            background: var(--light-2);
            font-weight: 600;
            color: var(--dark);
        }
        .items-table tfoot {
            background: var(--light-2);
        }
        .order-notes {
            background: var(--light);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
        }
        .order-notes h5 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        </style>';
    } else {
        echo '<div class="error-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Pesanan Tidak Ditemukan</h3>
                <p>Data pesanan dengan ID tersebut tidak ditemukan.</p>
              </div>';
    }
} else {
    echo '<div class="error-state">
            <i class="fas fa-exclamation-circle"></i>
            <h3>ID Pesanan Tidak Valid</h3>
            <p>Terjadi kesalahan dalam memuat detail pesanan.</p>
          </div>';
}

$conn->close();
?>