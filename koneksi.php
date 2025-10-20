<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "delondree_admin";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
