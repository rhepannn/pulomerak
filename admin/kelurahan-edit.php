<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/kelurahan.php');
$stmt=$conn->prepare("SELECT * FROM kelurahan WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$k=$stmt->get_result()->fetch_assoc();if(!$k)redirect(SITE_URL.'/admin/kelurahan.php');
$pageTitle='Edit Kelurahan';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama=trim($_POST['nama']??'');$ketua_pkk=trim($_POST['ketua_pkk']??'');$deskripsi=$_POST['deskripsi']??'';
    $inovasi=$_POST['inovasi']??'';$jumlah_rw=(int)($_POST['jumlah_rw']??0);
    $jumlah_rt=(int)($_POST['jumlah_rt']??0);$penduduk=(int)($_POST['penduduk']??0);
    $gambar=$k['gambar'];
    if(empty($nama)){$error='Nama wajib diisi!';}
    else{
        if(!empty($_FILES['gambar']['tmp_name'])){
            $up=uploadFile($_FILES['gambar'],'../uploads/kegiatan');
            if(is_array($up) && isset($up['error'])){
                $error = $up['error'];
            } else {
                if($gambar&&file_exists('../uploads/kegiatan/'.$gambar))unlink('../uploads/kegiatan/'.$gambar);
                $gambar=$up;
            }
        }
        $stmt=$conn->prepare("UPDATE kelurahan SET nama=?,ketua_pkk=?,deskripsi=?,inovasi=?,gambar=?,jumlah_rw=?,jumlah_rt=?,penduduk=? WHERE id=?");
        $stmt->bind_param('sssssiiii',$nama,$ketua_pkk,$deskripsi,$inovasi,$gambar,$jumlah_rw,$jumlah_rt,$penduduk,$id);
        if($stmt->execute()){setFlash('success','Data kelurahan berhasil diperbarui!');redirect(SITE_URL.'/admin/kelurahan.php');}
        else $error='Gagal: '.$conn->error;
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Edit Kelurahan</h1></div><a href="kelurahan.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Data Kelurahan</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Kelurahan / RW <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?=e($_POST['nama']??$k['nama'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ketua TP PKK</label>
                    <input type="text" name="ketua_pkk" class="form-control" value="<?=e($_POST['ketua_pkk']??$k['ketua_pkk']??'')?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Wilayah</label>
                    <?php if(!empty($k['gambar'])): ?>
                        <img src="<?=getImg($k['gambar'],'kegiatan')?>" id="prevImg" class="current-img" alt="">
                    <?php else: ?>
                        <img id="prevImg" src="" class="current-img" style="display:none" alt="">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg" style="margin-top:8px">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jumlah RW</label>
                    <input type="number" name="jumlah_rw" class="form-control" value="<?=e($_POST['jumlah_rw']??$k['jumlah_rw']??0)?>" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah RT</label>
                    <input type="number" name="jumlah_rt" class="form-control" value="<?=e($_POST['jumlah_rt']??$k['jumlah_rt']??0)?>" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Penduduk (Jiwa)</label>
                <input type="number" name="penduduk" class="form-control" value="<?=e($_POST['penduduk']??$k['penduduk']??0)?>" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Wilayah</label>
                <textarea name="deskripsi" class="form-control" rows="5"><?=e($_POST['deskripsi']??$k['deskripsi'])?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Inovasi Unggulan</label>
                <textarea name="inovasi" class="form-control" rows="4"><?=e($_POST['inovasi']??$k['inovasi'])?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Data</button>
                <a href="kelurahan.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
