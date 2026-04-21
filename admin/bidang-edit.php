<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL.'/admin/bidang.php');

$stmt = $conn->prepare("SELECT * FROM bidang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$b = $stmt->get_result()->fetch_assoc();

if (!$b) redirect(SITE_URL.'/admin/bidang.php');

$pageTitle = 'Edit Bidang: ' . $b['nama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi        = trim($_POST['deskripsi'] ?? '');
    $prestasi         = trim($_POST['prestasi'] ?? '');
    $program_unggulan = trim($_POST['program_unggulan'] ?? '');
    $gambar           = $b['gambar'];

    if (empty($deskripsi)) {
        $error = 'Deskripsi bidang wajib diisi!';
    } else {
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/bidang');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                if ($gambar && file_exists('../uploads/bidang/' . $gambar)) {
                    unlink('../uploads/bidang/' . $gambar);
                }
                $gambar = $up;
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE bidang SET deskripsi = ?, prestasi = ?, program_unggulan = ?, gambar = ? WHERE id = ?");
            $stmt->bind_param('ssssi', $deskripsi, $prestasi, $program_unggulan, $gambar, $id);
            if ($stmt->execute()) {
                setFlash('success', 'Data bidang berhasil diperbarui!');
                redirect(SITE_URL . '/admin/bidang.php');
            } else {
                $error = 'Gagal menyimpan: ' . $conn->error;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Edit Bidang</h1></div>
    <a href="bidang.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if(!empty($error)): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Informasi: <?= e($b['nama']) ?></div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Deskripsi Bidang <span>*</span></label>
                <textarea name="deskripsi" class="form-control" rows="6" required><?= e($_POST['deskripsi'] ?? $b['deskripsi']) ?></textarea>
                <p class="form-hint">Penjelasan mengenai tugas dan fungsi bidang ini.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Prestasi Daerah</label>
                <textarea name="prestasi" class="form-control" rows="3"><?= e($_POST['prestasi'] ?? $b['prestasi']) ?></textarea>
                <p class="form-hint">Contoh: "Juara 1 Lomba UP2K Tingkat Provinsi Banten Tahun 2024".</p>
            </div>

            <div class="form-group">
                <label class="form-label">Program Unggulan</label>
                <textarea name="program_unggulan" class="form-control" rows="6"><?= e($_POST['program_unggulan'] ?? $b['program_unggulan']) ?></textarea>
                <p class="form-hint">Pisahkan setiap program dengan baris baru. Gunakan format "1. Nama Program" agar tampil rapi di website.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Sampul Bidang</label>
                <?php if(!empty($b['gambar'])): ?>
                    <img src="<?= getImg($b['gambar'], 'bidang') ?>" id="prevImg" class="current-img" alt="" style="max-height: 200px; display: block; margin-bottom: 15px;">
                <?php else: ?>
                    <img id="prevImg" src="" class="current-img" style="display:none; max-height: 200px; margin-bottom: 15px;" alt="">
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*" onchange="previewImage(this, 'prevImg')">
                <p class="form-hint">Pilih foto yang merepresentasikan kegiatan bidang ini. Biarkan kosong jika tidak ingin mengganti.</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Data Bidang</button>
                <a href="bidang.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, targetId) {
    const preview = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'footer.php'; ?>
