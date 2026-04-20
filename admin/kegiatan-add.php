<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Tambah Kegiatan';

// Ambil daftar kelurahan untuk dropdown
$kels = $conn->query("SELECT id, nama FROM kelurahan ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $kategori     = trim($_POST['kategori'] ?? '');
    $tgl_kegiatan = $_POST['tgl_kegiatan'] ?? date('Y-m-d');
    $lokasi       = trim($_POST['lokasi'] ?? '');
    $kelurahan_id = (int)($_POST['kelurahan_id'] ?? 0);

    if (empty($judul)) { $error = 'Judul kegiatan wajib diisi!'; }
    else {
        $gambar = '';
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/kegiatan');
            if (!$up) $error = 'Gagal upload gambar!';
            else $gambar = $up;
        }
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO kegiatan (judul, deskripsi, kategori, gambar, tgl_kegiatan, lokasi, kelurahan_id) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param('ssssssi', $judul, $deskripsi, $kategori, $gambar, $tgl_kegiatan, $lokasi, $kelurahan_id);
            if ($stmt->execute()) { setFlash('success','Kegiatan berhasil ditambahkan!'); redirect(SITE_URL.'/admin/kegiatan.php'); }
            else $error = 'Gagal menyimpan: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title">
    <div><h1>Tambah Kegiatan</h1></div>
    <a href="kegiatan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<?php if(!empty($error)): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif; ?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-calendar-plus"></i> Form Tambah Kegiatan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Kegiatan <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??'')?>" placeholder="Masukkan judul kegiatan">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??'')?>"
                           placeholder="Contoh: Sosial, Kesehatan, Pendidikan" list="kegKat">
                    <datalist id="kegKat">
                        <option value="Sosial Kemasyarakatan">
                        <option value="Kesehatan">
                        <option value="Pendidikan">
                        <option value="Pemerintahan">
                        <option value="Lingkungan Hidup">
                        <option value="Ekonomi">
                        <option value="Olahraga & Seni">
                        <option value="Keagamaan">
                    </datalist>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Kegiatan</label>
                    <input type="date" name="tgl_kegiatan" class="form-control" value="<?=e($_POST['tgl_kegiatan']??date('Y-m-d'))?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="<?=e($_POST['lokasi']??'')?>" placeholder="Nama lokasi kegiatan">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kelurahan / RW Terkait</label>
                <select name="kelurahan_id" class="form-control">
                    <option value="0">-- Semua / Tidak Spesifik --</option>
                    <?php while($k=$kels->fetch_assoc()): ?>
                        <option value="<?=$k['id']?>" <?=(($_POST['kelurahan_id']??0)==$k['id'])?'selected':''?>><?=e($k['nama'])?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" class="form-control" rows="6" placeholder="Jelaskan kegiatan secara singkat..."><?=e($_POST['deskripsi']??'')?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Foto Kegiatan</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg">
                <p class="form-hint">Format JPG/PNG/WEBP, maks 5MB.</p>
                <img id="prevImg" src="" class="current-img" style="display:none;margin-top:8px" alt="">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Kegiatan</button>
                <a href="kegiatan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
