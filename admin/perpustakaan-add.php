<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Tambah Dokumen Perpustakaan';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$deskripsi=trim($_POST['deskripsi']??'');
    $kategori=trim($_POST['kategori']??'');$tgl_upload=$_POST['tgl_upload']??date('Y-m-d');
    if(empty($judul)){$error='Judul wajib diisi!';}
    else{
        $file='';
        if(!empty($_FILES['file']['tmp_name'])){
            $up=uploadDoc($_FILES['file'],'../uploads/perpustakaan');
            if(!$up)$error='Gagal upload file! Format PDF/DOC/XLS, maks 5MB.';
            else $file=$up;
        }
        if(empty($error)){
            $stmt=$conn->prepare("INSERT INTO perpustakaan (judul,deskripsi,kategori,file,tgl_upload) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss',$judul,$deskripsi,$kategori,$file,$tgl_upload);
            if($stmt->execute()){setFlash('success','Dokumen berhasil ditambahkan!');redirect(SITE_URL.'/admin/perpustakaan.php');}
            else $error='Gagal: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Tambah Dokumen Perpustakaan</h1></div><a href="perpustakaan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-book-medical"></i> Form Tambah Dokumen</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Dokumen <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??'')?>" placeholder="Judul dokumen / arsip">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??'')?>" list="perpKat" placeholder="Peraturan, Arsip, Panduan...">
                    <datalist id="perpKat"><option value="Peraturan Daerah"><option value="Surat Edaran"><option value="Arsip Kelurahan"><option value="Panduan Layanan"><option value="Laporan Tahunan"><option value="Dokumen Publik"></datalist>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Upload</label>
                <input type="date" name="tgl_upload" class="form-control" value="<?=e($_POST['tgl_upload']??date('Y-m-d'))?>" style="max-width:280px">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Dokumen</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Keterangan singkat isi dokumen..."><?=e($_POST['deskripsi']??'')?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">File Dokumen (PDF/DOC/XLS)</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <p class="form-hint">Format: PDF, DOC, DOCX, XLS, XLSX. Maks 5MB.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-upload"></i> Upload Dokumen</button>
                <a href="perpustakaan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
