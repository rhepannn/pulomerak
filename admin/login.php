<?php
require_once '../include/config.php';
require_once '../include/functions.php';

// Sudah login? redirect ke dashboard
if (isAdminLoggedIn()) redirect(SITE_URL . '/admin/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['nama'];
            $_SESSION['admin_role'] = $user['role'];
            setFlash('success', 'Selamat datang, ' . $user['nama'] . '!');
            redirect(SITE_URL . '/admin/index.php');
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon"><i class="fas fa-building-columns"></i></div>
            <h2>Admin Panel</h2>
            <p>Portal Informasi Kelurahan Pulomerak</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form" autocomplete="off">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Masukkan username" value="<?= e($_POST['username'] ?? '') ?>"
                       required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div style="position:relative;">
                    <input type="password" name="password" id="passInput" class="form-control"
                           placeholder="Masukkan password" required style="padding-right:42px;">
                    <button type="button" onclick="togglePass()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray);">
                        <i class="fas fa-eye" id="passIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard
            </button>
        </form>

        <div class="login-back">
            <a href="<?= SITE_URL ?>/"><i class="fas fa-arrow-left"></i> Kembali ke Website</a>
        </div>
    </div>
</div>
<script>
function togglePass() {
    const inp  = document.getElementById('passInput');
    const icon = document.getElementById('passIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
</body>
</html>
