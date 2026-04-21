<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL.'/admin/bidang.php');

$stmt = $conn->prepare("SELECT * FROM anggota_bidang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$a = $stmt->get_result()->fetch_assoc();

if (!$a) redirect(SITE_URL.'/admin/bidang.php');

$bidang_id = $a['bidang_id'];
$stmt = $conn->prepare("SELECT * FROM bidang WHERE id = ?");
$stmt->bind_param('i', $bidang_id);
$stmt->execute();
$bidang = $stmt->get_result()->fetch_assoc();

$pageTitle = 'Edit Anggota: ' . $a['nama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $no_hp   = trim($_POST['no_hp'] ?? '');
    $urutan  = (int)($_POST['urutan'] ?? 0);
    $foto    = $a['foto'];

    if (empty($nama) || empty($jabatan)) {
        $error = 'Nama and Jabatan wajib diisi!';
    } else {
        if (!empty($_FILES['foto']['tmp_name'])) {
            $up = uploadFile($_FILES['foto'], '../uploads/bidang');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                if ($foto && file_exists('../uploads/bidang/' . $foto)) {
                    unlink('../uploads/bidang/' . $foto);
                }
                $foto = $up;
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE anggota_bidang SET nama=?, jabatan=?, foto=?, no_hp=?, urutan=? WHERE id=?");
            $stmt->bind_param('ssssii', $nama, $jabatan, $foto, $no_hp, $urutan, $id);
            if ($stmt->execute()) {
                setFlash('success', 'Data anggota berhasil diperbarui!');
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
    <div><h1>Edit Anggota</h1></div>
    <a href="anggota.php?bidang_id=<?= $bidang_id ?>" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if(!empty($error)): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Data: <?= e($a['nama']) ?></div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? $a['nama']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan <span>*</span></label>
                    <input type="text" name="jabatan" class="form-control" required value="<?= e($_POST['jabatan'] ?? $a['jabatan']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= e($_POST['no_hp'] ?? $a['no_hp']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= (int)($_POST['urutan'] ?? $a['urutan']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Anggota</label>
                <?php if($a['foto']): ?>
                    <img src="<?= getImg($a['foto'], 'bidang') ?>" id="prevImg" class="current-img" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; display: block; margin-bottom: 15px;" alt="">
                <?php else: ?>
                    <img id="prevImg" src="" class="current-img" style="display:none; width: 150px; height: 150px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;" alt="">
                <?php endif; ?>
                <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(this, 'prevImg')">
                <p class="form-hint">Biarkan kosong jika tidak mengganti foto.</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Anggota</button>
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
