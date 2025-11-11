<?php
session_start();
if (!isset($_SESSION['adminLoggedIn'])) {
    header('Location: ../login.php');
    exit;
}
include '../koneksi.php';
include '../image_helper.php';

$id = $_POST['id'] ?? '';
$nama_layanan = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga_mulai = intval($_POST['harga_mulai']);
$foto = '';

// Handle file upload dengan smart resize
if (!empty($_FILES['foto']['name'])) {
    // Validasi file
    $validation = validateImageUpload($_FILES['foto'], 5242880); // 5MB
    
    if (!$validation['success']) {
        die($validation['message']);
    }
    
    $targetDir = "../uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $fileName = time() . "_layanan_" . uniqid() . "." . $ext;
    $tempFile = $_FILES["foto"]["tmp_name"];
    $targetFile = $targetDir . $fileName;
    
    // SMART RESIZE: 400x300px dengan mode 'cover'
    if (smartResizeImage($tempFile, $targetFile, 400, 300, 90, 'cover')) {
        $foto = $fileName;
        
        // Hapus foto lama jika update
        if ($id != '') {
            $getOld = $conn->prepare("SELECT foto FROM layanan WHERE id = ?");
            $getOld->bind_param("i", $id);
            $getOld->execute();
            $result = $getOld->get_result();
            if ($row = $result->fetch_assoc()) {
                $oldFile = $targetDir . $row['foto'];
                if (file_exists($oldFile) && $row['foto'] != '') {
                    unlink($oldFile);
                }
            }
            $getOld->close();
        }
    } else {
        die("Gagal memproses gambar.");
    }
}

// Simpan ke database
if ($id == '') {
    // INSERT
    if ($foto == '') {
        die("Foto harus diupload untuk layanan baru!");
    }
    
    $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, deskripsi, harga_mulai, foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nama_layanan, $deskripsi, $harga_mulai, $foto);
} else {
    // UPDATE
    if ($foto != '') {
        $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, deskripsi=?, harga_mulai=?, foto=? WHERE id=?");
        $stmt->bind_param("ssisi", $nama_layanan, $deskripsi, $harga_mulai, $foto, $id);
    } else {
        $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, deskripsi=?, harga_mulai=? WHERE id=?");
        $stmt->bind_param("ssii", $nama_layanan, $deskripsi, $harga_mulai, $id);
    }
}

if ($stmt->execute()) {
    echo "Data layanan berhasil disimpan.";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>