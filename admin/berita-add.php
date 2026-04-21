<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Tambah Berita';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul    = trim($_POST['judul'] ?? '');
    $isi      = trim($_POST['isi'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tgl_post = $_POST['tgl_post'] ?? date('Y-m-d');
    $url_src  = trim($_POST['url_sumber'] ?? '');

    if (empty($judul)) {
        $error = 'Judul berita wajib diisi!';
    } else {
        $isi = ''; // Set empty string for content
        $gambar = '';
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/berita');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                $gambar = $up;
            }
        }
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO berita (judul, isi, kategori, gambar, tgl_post, url_sumber) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param('ssssss', $judul, $isi, $kategori, $gambar, $tgl_post, $url_src);
            if ($stmt->execute()) {
                setFlash('success', 'Berita berhasil ditambahkan!');
                redirect(SITE_URL . '/admin/berita.php');
            } else {
                $error = 'Gagal menyimpan data: ' . $conn->error;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Tambah Berita</h1></div>
    <a href="berita.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
<?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-newspaper"></i> Form Tambah Berita</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Berita <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required
                           value="<?= e($_POST['judul'] ?? '') ?>" placeholder="Masukkan judul berita">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="<?= e($_POST['kategori'] ?? '') ?>"
                           placeholder="Contoh: Pemerintahan, Sosial, Kesehatan" list="katList">
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
                           value="<?= e($_POST['tgl_post'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">URL Sumber (Opsional)</label>
                    <input type="url" name="url_sumber" class="form-control"
                           value="<?= e($_POST['url_sumber'] ?? '') ?>" placeholder="https://...">
                    <p class="form-hint">Link ke sumber berita asli (jika ada)</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Berita</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="previewImg">
                <p class="form-hint">Format: JPG, JPEG, PNG, WEBP. Maks 5MB.</p>
                <img id="previewImg" src="" alt="Preview" class="current-img" style="display:none;margin-top:10px;">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Berita</button>
                <a href="berita.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
