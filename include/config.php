<?php
// ============================================================
// KONFIGURASI - Portal Informasi Kecamatan Pulomerak
// ============================================================

// ── DATABASE ─────────────────────────────────────────────────
define('DB_HOST', 'db.nohrhpgmdmefqndklqhw.supabase.co'); // Ganti dengan host Supabase Anda
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'kecamatanpulomerak12'); // Ganti dengan password database Supabase Anda
define('DB_NAME', 'postgres');

// ── SITE URL ─────────────────────────────────────────────────
$__p = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$__h = $_SERVER['HTTP_HOST'] ?? 'localhost';
$__r = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', str_replace('\\', '/', __DIR__));
$__r = rtrim(dirname($__r), '/');
define('SITE_URL', $__p . '://' . $__h . ($__r ? $__r : ''));
define('SITE_NAME', 'Portal Informasi Kecamatan Pulomerak');
define('SITE_DESC', 'Pusat Informasi Masyarakat Kecamatan Pulomerak, Kota Cilegon');
unset($__p);


// ── KONEKSI DATABASE (PDO POSTGRESQL) ────────────────────────
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    
    // Alias $conn ke $pdo untuk kemudahan transisi (meskipun berbeda API)
    $conn = $pdo; 

} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;">
        <h2 style="color:#c0392b;">Koneksi Supabase Gagal</h2>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
    </div>');
}
date_default_timezone_set('Asia/Jakarta');

// ── SESSION ──────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-setup schema dihilangkan karena Supabase/PostgreSQL 
// memerlukan migrasi manual via SQL Editor untuk performa terbaik.
// _ensureSchema($conn);
