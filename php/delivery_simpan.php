<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';

$id = $_POST['id'] ?? '';
$range_min = floatval($_POST['range_min']);
$range_max = floatval($_POST['range_max']);
$rate = intval($_POST['rate']);
$description = mysqli_real_escape_string($conn, $_POST['description']);

if ($id == '') {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO delivery_rates (range_min, range_max, rate, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ddss", $range_min, $range_max, $rate, $description);
} else {
    // UPDATE
    $stmt = $conn->prepare("UPDATE delivery_rates SET range_min=?, range_max=?, rate=?, description=? WHERE id=?");
    $stmt->bind_param("ddssi", $range_min, $range_max, $rate, $description, $id);
}

if ($stmt->execute()) {
    echo "Data biaya antar jemput berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>