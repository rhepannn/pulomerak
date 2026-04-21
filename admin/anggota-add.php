<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$bidang_id = (int)($_GET['bidang_id'] ?? 0);
if (!$bidang_id) redirect(SITE_URL.'/admin/bidang.php');

$stmt = $conn->prepare("SELECT * FROM bidang WHERE id = ?");
$stmt->bind_param('i', $bidang_id);
$stmt->execute();
$bidang = $stmt->get_result()->fetch_assoc();

if (!$bidang) redirect(SITE_URL.'/admin/bidang.php');

$pageTitle = 'Tambah Anggota: ' . $bidang['nama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $no_hp   = trim($_POST['no_hp'] ?? '');
    $urutan  = (int)($_POST['urutan'] ?? 0);
    $foto    = '';

    if (empty($nama) || empty($jabatan)) {
        $error = 'Nama and Jabatan wajib diisi!';
    } else {
        if (!empty($_FILES['foto']['tmp_name'])) {
            $up = uploadFile($_FILES['foto'], '../uploads/bidang');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                $foto = $up;
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO anggota_bidang (bidang_id, nama, jabatan, foto, no_hp, urutan) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('issssi', $bidang_id, $nama, $jabatan, $foto, $no_hp, $urutan);
            if ($stmt->execute()) {
                setFlash('success', 'Anggota berhasil ditambahkan!');
                redirect(SITE_URL . '/admin/anggota.php?bidang_id=' . $bidang_id);
            } else {
                $error = 'Gagal menyimpan: ' . $conn->error;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Tambah Anggota</h1></div>
    <a href="anggota.php?bidang_id=<?= $bidang_id ?>" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if(!empty($error)): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-user-plus"></i> Tambah Anggota ke <?= e($bidang['nama']) ?></div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan <span>*</span></label>
                    <input type="text" name="jabatan" class="form-control" required value="<?= e($_POST['jabatan'] ?? '') ?>" placeholder="Contoh: Ketua POKJA, Sekretaris, dll">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= e($_POST['no_hp'] ?? '') ?>" placeholder="Contoh: 08123456789">
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= (int)($_POST['urutan'] ?? 0) ?>">
                    <p class="form-hint">Angka lebih kecil tampil lebih awal (misal: 1, 2, 3).</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Anggota</label>
                <img id="prevImg" src="" class="current-img" style="display:none; width: 150px; height: 150px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;" alt="">
                <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(this, 'prevImg')">
                <p class="form-hint">Disarankan foto dengan rasio 1:1 (persegi).</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Anggota</button>
                <a href="anggota.php?bidang_id=<?= $bidang_id ?>" class="btn-cancel">Batal</a>
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
