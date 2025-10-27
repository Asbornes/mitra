<?php
session_start();
include 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true) {
    header('Location: admin.php');
    exit;
}

$login_error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Query dengan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Verifikasi password (MD5 sesuai dengan sistem yang ada)
        if (md5($password) === $admin['password']) {
            // Set session
            $_SESSION['adminLoggedIn'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            $stmt->close();
            $conn->close();
            
            header('Location: admin.php');
            exit;
        } else {
            $login_error = 'Username atau password salah!';
        }
    } else {
        $login_error = 'Username atau password salah!';
    }
    
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - deLondree</title>
    <link rel="stylesheet" href="login.css">
</head>
<body class="login-page">
    <button class="back-button" onclick="window.location.href='index.php'">
        <span class="back-icon">←</span> Kembali ke Beranda
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="hero.jpg" alt="deLondree Logo" class="login-logo">
                <h1>Admin Panel</h1>
                <p>Masuk ke dashboard admin deLondree</p>
            </div>

            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-container">
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">
                        <span class="input-icon">👤</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                        <span class="input-icon">🔒</span>
                    </div>
                </div>

                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>

                <button type="submit" class="btn-primary">
                    <span class="btn-text">Login</span>
                    <span class="btn-icon">→</span>
                </button>
            </form>

            <div class="login-footer">
                <small>© 2025 deLondree - Secure Admin Panel</small>
            </div>
        </div>
    </div>
</body>
</html>