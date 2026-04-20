<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/laporan.php');
$stmt=$conn->prepare("SELECT * FROM laporan WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$l=$stmt->get_result()->fetch_assoc();if(!$l)redirect(SITE_URL.'/admin/laporan.php');
$pageTitle='Edit Laporan';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$deskripsi=trim($_POST['deskripsi']??'');$tgl_upload=$_POST['tgl_upload']??date('Y-m-d');
    $file=$l['file'];
    if(empty($judul)){$error='Judul wajib diisi!';}
    else{
        if(!empty($_FILES['file']['tmp_name'])){
            $up=uploadDoc($_FILES['file'],'../uploads/laporan');
            if(!$up)$error='Gagal upload file!';
            else{if($file&&file_exists('../uploads/laporan/'.$file))unlink('../uploads/laporan/'.$file);$file=$up;}
        }
        if(empty($error)){
            $stmt=$conn->prepare("UPDATE laporan SET judul=?,deskripsi=?,file=?,tgl_upload=? WHERE id=?");
            $stmt->bind_param('ssssi',$judul,$deskripsi,$file,$tgl_upload,$id);
            if($stmt->execute()){setFlash('success','Laporan berhasil diperbarui!');redirect(SITE_URL.'/admin/laporan.php');}
            else $error='Gagal: '.$conn->error;
        }
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Edit Laporan</h1></div><a href="laporan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Data Laporan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Laporan <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??$l['judul'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_upload" class="form-control" value="<?=e($_POST['tgl_upload']??$l['tgl_upload'])?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?=e($_POST['deskripsi']??$l['deskripsi'])?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Ganti File (Opsional)</label>
                <?php if(!empty($l['file'])): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--light);border-radius:8px;margin-bottom:10px;">
                        <i class="fas fa-file-pdf" style="color:#c0392b;font-size:1.4rem"></i>
                        <div>
                            <div style="font-weight:700;font-size:0.875rem"><?=e($l['file'])?></div>
                            <a href="<?=SITE_URL?>/uploads/laporan/<?=e($l['file'])?>" target="_blank" style="font-size:0.78rem;color:var(--p)">Lihat File Saat Ini</a>
                        </div>
                    </div>
                <?php endif; ?>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                <p class="form-hint">Biarkan kosong jika tidak mengganti file.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Laporan</button>
                <a href="laporan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
