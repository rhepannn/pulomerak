<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL.'/admin/kegiatan.php');
$stmt = $conn->prepare("SELECT * FROM kegiatan WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute();
$k = $stmt->get_result()->fetch_assoc();
if (!$k) redirect(SITE_URL.'/admin/kegiatan.php');

$pageTitle = 'Edit Kegiatan';
$kels = $conn->query("SELECT id, nama FROM kelurahan ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul        = trim($_POST['judul'] ?? '');
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $kategori     = trim($_POST['kategori'] ?? '');
    $tgl_kegiatan = $_POST['tgl_kegiatan'] ?? date('Y-m-d');
    $lokasi       = trim($_POST['lokasi'] ?? '');
    $kelurahan_id = (int)($_POST['kelurahan_id'] ?? 0);
    $gambar       = $k['gambar'];

    if (empty($judul)) { $error = 'Judul wajib diisi!'; }
    else {
        if (!empty($_FILES['gambar']['tmp_name'])) {
            $up = uploadFile($_FILES['gambar'], '../uploads/kegiatan');
            if (!$up) $error = 'Gagal upload gambar!';
            else {
                if ($gambar && file_exists('../uploads/kegiatan/'.$gambar)) unlink('../uploads/kegiatan/'.$gambar);
                $gambar = $up;
            }
        }
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE kegiatan SET judul=?,deskripsi=?,kategori=?,gambar=?,tgl_kegiatan=?,lokasi=?,kelurahan_id=? WHERE id=?");
            $stmt->bind_param('ssssssii',$judul,$deskripsi,$kategori,$gambar,$tgl_kegiatan,$lokasi,$kelurahan_id,$id);
            if ($stmt->execute()) { setFlash('success','Kegiatan berhasil diperbarui!'); redirect(SITE_URL.'/admin/kegiatan.php'); }
            else $error = 'Gagal menyimpan: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title">
    <div><h1>Edit Kegiatan</h1></div>
    <a href="kegiatan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<?php if(!empty($error)): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif; ?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Data Kegiatan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Kegiatan <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??$k['judul'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??$k['kategori'])?>" list="kegKat">
                    <datalist id="kegKat"><option value="Sosial Kemasyarakatan"><option value="Kesehatan"><option value="Pendidikan"><option value="Pemerintahan"><option value="Lingkungan Hidup"><option value="Olahraga & Seni"></datalist>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal Kegiatan</label>
                    <input type="date" name="tgl_kegiatan" class="form-control" value="<?=e($_POST['tgl_kegiatan']??$k['tgl_kegiatan'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="<?=e($_POST['lokasi']??$k['lokasi'])?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kelurahan / RW Terkait</label>
                <select name="kelurahan_id" class="form-control">
                    <option value="0">-- Semua --</option>
                    <?php $kels->data_seek(0); while($kl=$kels->fetch_assoc()): ?>
                        <option value="<?=$kl['id']?>" <?=(($_POST['kelurahan_id']??$k['kelurahan_id'])==$kl['id'])?'selected':''?>><?=e($kl['nama'])?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="6"><?=e($_POST['deskripsi']??$k['deskripsi'])?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Foto Kegiatan</label>
                <?php if(!empty($k['gambar'])): ?>
                    <img src="<?=getImg($k['gambar'],'kegiatan')?>" id="prevImg" class="current-img" alt="">
                <?php else: ?>
                    <img id="prevImg" src="" class="current-img" style="display:none" alt="">
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg" style="margin-top:8px">
                <p class="form-hint">Biarkan kosong jika tidak mengganti foto.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Kegiatan</button>
                <a href="kegiatan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
