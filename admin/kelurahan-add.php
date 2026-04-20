<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Tambah Kelurahan';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');
    $inovasi    = trim($_POST['inovasi'] ?? '');
    $jumlah_rw  = (int)($_POST['jumlah_rw'] ?? 0);
    $jumlah_rt  = (int)($_POST['jumlah_rt'] ?? 0);
    $penduduk   = (int)($_POST['penduduk'] ?? 0);

    if (empty($nama)) {
        $error = 'Nama kelurahan wajib diisi!';
    } else {
        $gambar = '';
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/kegiatan');
            if ($up) $gambar = $up;
        }
        $stmt = $conn->prepare("INSERT INTO kelurahan (nama, deskripsi, inovasi, gambar, jumlah_rw, jumlah_rt, penduduk) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssiii', $nama, $deskripsi, $inovasi, $gambar, $jumlah_rw, $jumlah_rt, $penduduk);
        if ($stmt->execute()) {
            setFlash('success', 'Data kelurahan berhasil ditambahkan!');
            redirect(SITE_URL . '/admin/kelurahan.php');
        } else {
            $error = 'Gagal menyimpan: ' . $conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title">
    <div><h1>Tambah Kelurahan / RW</h1></div>
    <a href="kelurahan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<?php if (!empty($error)): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
<?php endif; ?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-city"></i> Form Tambah Kelurahan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Kelurahan / RW <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? '') ?>" placeholder="Contoh: RW 01 Kampung Merak">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Wilayah</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg">
                    <img id="prevImg" src="" class="current-img" style="display:none;margin-top:8px" alt="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah RW</label>
                    <input type="number" name="jumlah_rw" class="form-control" value="<?= e($_POST['jumlah_rw'] ?? 0) ?>" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah RT</label>
                    <input type="number" name="jumlah_rt" class="form-control" value="<?= e($_POST['jumlah_rt'] ?? 0) ?>" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Penduduk (Jiwa)</label>
                <input type="number" name="penduduk" class="form-control" value="<?= e($_POST['penduduk'] ?? 0) ?>" min="0" placeholder="Jumlah jiwa">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Wilayah</label>
                <textarea name="deskripsi" class="form-control" rows="5" placeholder="Deskripsikan wilayah..."><?= e($_POST['deskripsi'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Inovasi Unggulan</label>
                <textarea name="inovasi" class="form-control" rows="4" placeholder="Ceritakan program/inovasi unggulan di wilayah ini..."><?= e($_POST['inovasi'] ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Data</button>
                <a href="kelurahan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
