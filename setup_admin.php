<?php
// Script untuk membuat akun admin pertama
// HAPUS FILE INI SETELAH DIJALANKAN!
// Jalankan sekali: http://localhost/pulomerak/setup_admin.php

require_once 'include/config.php';

$username = 'masbro';
$password = '12';
$nama     = 'Administrator';
$role     = 'Superadmin';

// Cek apakah sudah ada admin
$cek = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
if ($cek > 0) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;color:#c0392b;">
        <h2>⚠️ Setup sudah pernah dijalankan!</h2>
        <p>Sudah ada ' . $cek . ' akun admin. Hapus file ini.</p>
    </div>');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (nama, username, password, role) VALUES (?,?,?,?)");
$stmt->bind_param('ssss', $nama, $username, $hash, $role);

if ($stmt->execute()) {
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
    <title>Setup Berhasil</title>
    <style>
        body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#f0f4fb;}
        .card{background:#fff;border-radius:16px;padding:40px;max-width:480px;box-shadow:0 8px 32px rgba(0,0,0,0.1);text-align:center;}
        h2{color:#1a4fa0;margin-bottom:16px;} .cred{background:#f0f4fb;border-radius:10px;padding:16px;margin:20px 0;text-align:left;}
        .cred div{margin:6px 0;font-size:0.95rem;} .warn{color:#c0392b;font-size:0.85rem;font-weight:700;margin-top:16px;}
        a{display:inline-block;margin-top:16px;padding:12px 28px;background:#1a4fa0;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;}
    </style></head><body>
    <div class="card">
        <h2>✅ Setup Admin Berhasil!</h2>
        <p>Akun administrator berhasil dibuat.</p>
        <div class="cred">
            <div><strong>Username:</strong> ' . $username . '</div>
            <div><strong>Password:</strong> ' . $password . '</div>
            <div style="color:#e67e22;font-size:0.8rem;margin-top:8px">⚠️ Password ini sangat lemah, pertimbangkan untuk mengganti setelah login.</div>
        </div>
        <p class="warn">⚠️ PENTING: Segera hapus file setup_admin.php dari server setelah login!</p>
        <a href="admin/login.php">Login ke Admin Panel →</a>
    </div></body></html>';
} else {
    echo '<p style="color:red">Gagal membuat akun: ' . $conn->error . '</p>';
}
?>
