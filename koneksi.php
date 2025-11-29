<?php
// koneksi.php - Database Connection
$host = "localhost";
$username = "root";
$password = "";
$database = "delondree_admin";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Return JSON error instead of dying
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit;
}

// Set charset to utf8
$conn->set_charset("utf8mb4");

// Error reporting (disable in production)
error_reporting(0); // Nonaktifkan error reporting untuk produksi
ini_set('display_errors', 0);
?>