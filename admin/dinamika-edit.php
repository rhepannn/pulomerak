<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/dinamika.php');
$stmt=$conn->prepare("SELECT * FROM dinamika WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$d=$stmt->get_result()->fetch_assoc();if(!$d)redirect(SITE_URL.'/admin/dinamika.php');
$pageTitle='Edit Artikel Dinamika';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$isi=trim($_POST['isi']??'');
    $kategori=trim($_POST['kategori']??'');$penulis=trim($_POST['penulis']??'');
    $tgl_post=$_POST['tgl_post']??date('Y-m-d');$gambar=$d['gambar'];
    if(empty($judul)||empty($isi)){$error='Judul dan isi wajib diisi!';}
    else{
        if(!empty($_FILES['gambar']['tmp_name'])){
            $up=uploadFile($_FILES['gambar'],'../uploads/kegiatan');
            if($up){if($gambar&&file_exists('../uploads/kegiatan/'.$gambar))unlink('../uploads/kegiatan/'.$gambar);$gambar=$up;}
        }
        $stmt=$conn->prepare("UPDATE dinamika SET judul=?,isi=?,kategori=?,penulis=?,gambar=?,tgl_post=? WHERE id=?");
        $stmt->bind_param('ssssssi',$judul,$isi,$kategori,$penulis,$gambar,$tgl_post,$id);
        if($stmt->execute()){setFlash('success','Artikel berhasil diperbarui!');redirect(SITE_URL.'/admin/dinamika.php');}
        else $error='Gagal: '.$conn->error;
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Edit Artikel Dinamika</h1></div><a href="dinamika.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-edit"></i> Edit Artikel Dinamika</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Artikel <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??$d['judul'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??$d['kategori'])?>" list="dynKat">
                    <datalist id="dynKat"><option value="Sosial"><option value="Budaya"><option value="Ekonomi"><option value="Lingkungan"><option value="Kesehatan Masyarakat"></datalist>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?=e($_POST['penulis']??$d['penulis'])?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_post" class="form-control" value="<?=e($_POST['tgl_post']??$d['tgl_post'])?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Isi Artikel <span>*</span></label>
                <textarea name="isi" class="form-control" rows="10" required><?=e($_POST['isi']??$d['isi'])?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Ilustrasi</label>
                <?php if(!empty($d['gambar'])): ?><img src="<?=getImg($d['gambar'],'kegiatan')?>" id="prevImg" class="current-img" alt=""><?php else: ?><img id="prevImg" src="" class="current-img" style="display:none" alt=""><?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg" style="margin-top:8px">
                <p class="form-hint">Biarkan kosong jika tidak mengganti gambar.</p>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Perbarui Artikel</button>
                <a href="dinamika.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
