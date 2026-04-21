<?php
// ============================================================
// KONFIGURASI DATABASE - Portal Informasi Kelurahan Pulomerak
// Ganti nilai di bawah sesuai hosting InfinityFree Anda
// ============================================================

define('DB_HOST',     'localhost');       // Host database
define('DB_USER',     'root');            // Username database (InfinityFree: username akun)
define('DB_PASS',     '');                // Password database
define('DB_NAME',     'pulomerak');    // Nama database

define('SITE_URL',    'http://localhost/PuloMerak/pulomerak'); // Ganti dengan domain Anda
define('SITE_NAME',   'Portal Informasi Kecamatan Pulomerak');
define('SITE_DESC',   'Pusat Informasi Masyarakat Kecamatan Pulomerak, Kota Cilegon');

// Karena MySQL di XAMPP ini jalan di port 3307, kita tambahkan port-nya
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3307);

// Cek koneksi
if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;">
        <h2 style="color:#c0392b;">Database Connection Failed</h2>
        <p>' . htmlspecialchars($conn->connect_error) . '</p>
        <p>Pastikan database sudah diimport dan konfigurasi di <code>include/config.php</code> sudah benar.</p>
    </div>');
}

// Set charset UTF-8
$conn->set_charset('utf8mb4');

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
