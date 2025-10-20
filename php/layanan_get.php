<?php
include '../koneksi.php';
$id = intval($_GET['id'] ?? 0);

$q = $conn->query("SELECT * FROM layanan WHERE id = $id");
if ($q && $q->num_rows > 0) {
    echo json_encode($q->fetch_assoc());
} else {
    echo json_encode(null);
}
?>
