<?php
// ============================================================
// FUNGSI HELPER - Portal Informasi Kelurahan Pulomerak
// ============================================================

/**
 * Sanitasi output untuk mencegah XSS
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal($date, $format = 'full') {
    if (!$date) return '-';
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $ts = strtotime($date);
    $d  = date('j', $ts);
    $m  = $bulan[(int)date('n', $ts)];
    $y  = date('Y', $ts);
    if ($format === 'short') return "$d $m $y";
    return "$d $m $y";
}

/**
 * Truncate teks
 */
function truncate($str, $len = 150) {
    if (!$str) return '';
    $str = strip_tags($str);
    if (mb_strlen($str) <= $len) return $str;
    return mb_substr($str, 0, $len) . '...';
}

/**
 * Slug sederhana
 */
function createSlug($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', $str);
    return $str;
}

/**
 * Upload file
 */
function uploadFile($file, $dir, $allowedTypes = ['jpg','jpeg','png','gif','webp']) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) return false;
    if ($file['size'] > 5 * 1024 * 1024) return false; // Max 5MB
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $dest = $dir . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) return $filename;
    return false;
}

/**
 * Upload file PDF atau dokumen
 */
function uploadDoc($file, $dir) {
    return uploadFile($file, $dir, ['pdf', 'doc', 'docx', 'xls', 'xlsx']);
}

/**
 * Redirect
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Flash message
 */
function setFlash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function showFlash() {
    $f = getFlash();
    if (!$f) return '';
    $cls = $f['type'] === 'success' ? 'alert-success' : 'alert-error';
    return '<div class="alert ' . $cls . '">' . e($f['msg']) . '</div>';
}

/**
 * Cek apakah admin sudah login
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Proteksi halaman admin
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        redirect(SITE_URL . '/admin/login.php');
    }
}

/**
 * Ambil gambar atau default
 */
function getImg($file, $subdir = 'berita') {
    $path = __DIR__ . '/../uploads/' . $subdir . '/' . $file;
    if ($file && file_exists($path)) {
        return SITE_URL . '/uploads/' . $subdir . '/' . $file;
    }
    return SITE_URL . '/assets/img/placeholder.jpg';
}

/**
 * Pagination
 */
function paginate($total, $page, $perPage, $url = '?') {
    $totalPages = ceil($total / $perPage);
    if ($totalPages <= 1) return '';
    $html = '<div class="pagination">';
    if ($page > 1) $html .= '<a href="' . $url . 'page=' . ($page - 1) . '" class="page-btn">&#8592; Prev</a>';
    for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
        $active = $i == $page ? ' active' : '';
        $html .= '<a href="' . $url . 'page=' . $i . '" class="page-btn' . $active . '">' . $i . '</a>';
    }
    if ($page < $totalPages) $html .= '<a href="' . $url . 'page=' . ($page + 1) . '" class="page-btn">Next &#8594;</a>';
    $html .= '</div>';
    return $html;
}
?>
