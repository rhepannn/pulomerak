<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/perpustakaan.php');
$stmt=$conn->prepare("SELECT * FROM perpustakaan WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$p=$stmt->get_result()->fetch_assoc();if(!$p)redirect(SITE_URL.'/admin/perpustakaan.php');
$pageTitle='Edit Dokumen';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$deskripsi=trim($_POST['deskripsi']??'');
    $kategori=trim($_POST['kategori']??'');$tgl_upload=$_POST['tgl_upload']??date('Y-m-d');$file=$p['file'];
    if(empty($judul)){$error='Judul wajib diisi!';}
    else{
        if(!empty($_FILES['file']['tmp_name'])){
            $up=uploadDoc($_FILES['file'],'../uploads/perpustakaan');
            if(!$up)$error='Gagal upload!';
            else{if($file&&file_exists('../uploads/perpustakaan/'.$file))unlink('../uploads/perpustakaan/'.$file);$file=$up;}
        }
        if(empty($error)){
            $stmt=$conn->prepare("UPDATE perpustakaan SET judul=?,deskripsi=?,kategori=?,file=?,tgl_upload=? WHERE id=?");
            $stmt->bind_param('sssssi',$judul,$deskripsi,$kategori,$file,$tgl_upload,$id);
            if($stmt->execute()){setFlash('success','Dokumen berhasil diperbarui!');redirect(SITE_URL.'/admin/perpustakaan.php');}
            else $error='Gagal: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Edit Dokumen</h1></div><a href="perpustakaan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Dokumen Perpustakaan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Dokumen <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??$p['judul'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??$p['kategori'])?>" list="perpKat">
                    <datalist id="perpKat"><option value="Peraturan Daerah"><option value="Surat Edaran"><option value="Arsip Kelurahan"><option value="Panduan Layanan"><option value="Laporan Tahunan"></datalist>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Upload</label>
                <input type="date" name="tgl_upload" class="form-control" value="<?=e($_POST['tgl_upload']??$p['tgl_upload'])?>" style="max-width:280px">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?=e($_POST['deskripsi']??$p['deskripsi'])?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Ganti File (Opsional)</label>
                <?php if(!empty($p['file'])): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--light);border-radius:8px;margin-bottom:10px;">
                        <i class="fas fa-file-pdf" style="color:#c0392b;font-size:1.4rem"></i>
                        <div>
                            <div style="font-weight:700;font-size:0.875rem"><?=e($p['file'])?></div>
                            <a href="<?=SITE_URL?>/uploads/perpustakaan/<?=e($p['file'])?>" target="_blank" style="font-size:0.78rem;color:var(--p)">Lihat File Saat Ini</a>
                        </div>
                    </div>
                <?php endif; ?>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <p class="form-hint">Biarkan kosong jika tidak mengganti file.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Dokumen</button>
                <a href="perpustakaan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
