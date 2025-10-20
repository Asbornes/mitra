<?php
session_start();

// Jika sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true) {
    header('Location: admin.php');
    exit;
}

// Username & password default
$default_username = 'admin';
$default_password = 'admin123';
$login_error = '';

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');

    if ($username === $default_username && $password === $default_password) {
        $_SESSION['adminLoggedIn'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $login_error = 'Username atau password salah!';
    }
}
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
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                        <span class="input-icon">👤</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        <span class="input-icon">🔒</span>
                    </div>
                </div>

                <?php if (!empty($login_error)): ?>
                    <div class="alert alert-error"><?= $login_error ?></div>
                <?php endif; ?>

                <button type="submit" class="btn-primary">
                    <span class="btn-text">Login</span>
                    <span class="btn-icon">→</span>
                </button>
            </form>

            <div class="login-footer">
                <small>Default: admin / admin123</small>
            </div>
        </div>
    </div>
</body>
</html>