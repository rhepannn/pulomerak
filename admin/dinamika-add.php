<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Tambah Artikel Dinamika';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$isi=trim($_POST['isi']??'');
    $kategori=trim($_POST['kategori']??'');$penulis=trim($_POST['penulis']??'Admin');
    $tgl_post=$_POST['tgl_post']??date('Y-m-d');
    if(empty($judul)||empty($isi)){$error='Judul dan isi wajib diisi!';}
    else{
        $gambar='';
        if(!empty($_FILES['gambar']['tmp_name'])) {
            $up=uploadFile($_FILES['gambar'],'../uploads/kegiatan');
            if (is_array($up) && isset($up['error'])) {
                $error = $up['error'];
            } else {
                $gambar=$up;
            }
        }
        $stmt=$conn->prepare("INSERT INTO dinamika (judul,isi,kategori,penulis,gambar,tgl_post) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssss',$judul,$isi,$kategori,$penulis,$gambar,$tgl_post);
        if($stmt->execute()){setFlash('success','Artikel berhasil ditambahkan!');redirect(SITE_URL.'/admin/dinamika.php');}
        else $error='Gagal: '.$conn->error;
    }
}
include 'header.php';
?>
<div class="page-title"><div><h1>Tambah Artikel Dinamika</h1></div><a href="dinamika.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=e($error)?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-users"></i> Form Tambah Artikel Dinamika</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Artikel <span>*</span></label>
                    <input type="text" name="judul" class="form-control" required value="<?=e($_POST['judul']??'')?>" placeholder="Judul artikel dinamika">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?=e($_POST['kategori']??'')?>"
                           placeholder="Sosial, Budaya, Ekonomi..." list="dynKat">
                    <datalist id="dynKat"><option value="Sosial"><option value="Budaya"><option value="Ekonomi"><option value="Lingkungan"><option value="Kesehatan Masyarakat"><option value="Pendidikan"></datalist>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-control" value="<?=e($_POST['penulis']??'Admin Kelurahan')?>" placeholder="Nama penulis">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Posting</label>
                    <input type="date" name="tgl_post" class="form-control" value="<?=e($_POST['tgl_post']??date('Y-m-d'))?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Isi Artikel <span>*</span></label>
                <textarea name="isi" class="form-control" rows="10" required placeholder="Tulis artikel di sini..."><?=e($_POST['isi']??'')?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Ilustrasi</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg">
                <p class="form-hint">Format JPG/PNG/WEBP, maks 5MB.</p>
                <img id="prevImg" src="" class="current-img" style="display:none;margin-top:8px" alt="">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Artikel</button>
                <a href="dinamika.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
