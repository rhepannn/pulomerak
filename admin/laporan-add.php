<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Upload Laporan';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');
    $deskripsi=trim($_POST['deskripsi']??'');
    $tgl_upload=$_POST['tgl_upload']??date('Y-m-d');
    if(empty($judul)){$error='Judul laporan wajib diisi!';}
    else{
        $file='';
        if(!empty($_FILES['file']['tmp_name'])){
            $up=uploadDoc($_FILES['file'],'../uploads/laporan');
            if(!$up)$error='Gagal upload file! Format PDF/DOC/XLS, maks 5MB.';
            else $file=$up;
        }
        if(empty($error)){
            $stmt=$conn->prepare("INSERT INTO laporan (judul,deskripsi,file,tgl_upload) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss',$judul,$deskripsi,$file,$tgl_upload);
            if($stmt->execute()){setFlash('success','Laporan berhasil diupload!');redirect(SITE_URL.'/admin/laporan.php');}
            else $error='Gagal menyimpan: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title">
    <div><h1>Upload Laporan</h1></div>
    <a href="laporan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-file-upload"></i> Form Upload Laporan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Laporan <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??'')?>" placeholder="Contoh: Laporan Bulanan April 2025">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_upload" class="form-control" value="<?=e($_POST['tgl_upload']??date('Y-m-d'))?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Laporan</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Keterangan singkat tentang laporan ini..."><?=e($_POST['deskripsi']??'')?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">File Laporan (PDF/DOC/XLS)</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <p class="form-hint">Format: PDF, DOC, DOCX, XLS, XLSX. Maks 5MB.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-upload"></i> Upload Laporan</button>
                <a href="laporan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
