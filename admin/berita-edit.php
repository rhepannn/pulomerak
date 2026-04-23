<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL . '/admin/berita.php');

$stmt = $conn->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$berita = $stmt->get_result()->fetch_assoc();
if (!$berita) redirect(SITE_URL . '/admin/berita.php');

// CEK AKSES
checkOwnership($berita['kelurahan_id']);

$pageTitle = 'Edit Berita';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul    = trim($_POST['judul'] ?? '');
    $isi      = trim($_POST['isi'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tgl_post = $_POST['tgl_post'] ?? date('Y-m-d');
    $url_src  = trim($_POST['url_sumber'] ?? '');
    $gambar   = $berita['gambar'];

    if (empty($judul)) {
        $error = 'Judul wajib diisi!';
    } else {
        $isi = ''; // Set empty string as we are removing the content box
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/berita');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                // Hapus gambar lama
                if ($gambar && file_exists('../uploads/berita/' . $gambar)) unlink('../uploads/berita/' . $gambar);
                $gambar = $up;
            }
        }
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE berita SET judul=?,isi=?,kategori=?,gambar=?,tgl_post=?,url_sumber=? WHERE id=?");
            $stmt->bind_param('ssssssi', $judul, $isi, $kategori, $gambar, $tgl_post, $url_src, $id);
            if ($stmt->execute()) {
                setFlash('success', 'Berita berhasil diperbarui!');
                redirect(SITE_URL . '/admin/berita.php');
            } else {
                $error = 'Gagal menyimpan: ' . $conn->error;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Edit Berita</h1></div>
    <a href="berita.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
<?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Data Berita</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Berita <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required
                           value="<?= e($_POST['judul'] ?? $berita['judul']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="<?= e($_POST['kategori'] ?? $berita['kategori']) ?>" list="katList">
                    <datalist id="katList">
                        <option value="Pemerintahan">
                        <option value="Sosial Kemasyarakatan">
                        <option value="Kesehatan">
                        <option value="Pendidikan">
                        <option value="Infrastruktur">
                        <option value="Ekonomi">
                        <option value="Budaya">
                        <option value="Pengumuman">
                    </datalist>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Posting</label>
                    <input type="date" name="tgl_post" class="form-control"
                           value="<?= e($_POST['tgl_post'] ?? $berita['tgl_post']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">URL Sumber (Opsional)</label>
                    <input type="url" name="url_sumber" class="form-control"
                           value="<?= e($_POST['url_sumber'] ?? $berita['url_sumber']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Berita</label>
                <?php if (!empty($berita['gambar'])): ?>
                    <img src="<?= getImg($berita['gambar'], 'berita') ?>" id="previewImg" class="current-img" alt="">
                <?php else: ?>
                    <img id="previewImg" src="" class="current-img" style="display:none" alt="">
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*"
                       data-preview="previewImg" style="margin-top:8px;">
                <p class="form-hint">Biarkan kosong jika tidak ingin mengganti gambar. JPG/JPEG/PNG/WEBP. Maks 5MB.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Berita</button>
                <a href="berita.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
