<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Upload Foto Galeri';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $judul=trim($_POST['judul']??'');$keterangan=trim($_POST['keterangan']??'');
    $errors=[];
    if(isset($_FILES['gambar']) && is_array($_FILES['gambar']['name'])){
        $uploaded=0;
        foreach($_FILES['gambar']['name'] as $i=>$name){
            if(empty($_FILES['gambar']['tmp_name'][$i]))continue;
            $file=['name'=>$name,'type'=>$_FILES['gambar']['type'][$i],
                   'tmp_name'=>$_FILES['gambar']['tmp_name'][$i],'error'=>$_FILES['gambar']['error'][$i],
                   'size'=>$_FILES['gambar']['size'][$i]];
            $up=uploadFile($file,'../uploads/galeri');
            if($up){
                $tit=$judul?:pathinfo($name,PATHINFO_FILENAME);
                $stmt=$conn->prepare("INSERT INTO galeri (judul,keterangan,gambar) VALUES (?,?,?)");
                $stmt->bind_param('sss',$tit,$keterangan,$up);$stmt->execute();$uploaded++;
            } else $errors[]="Gagal upload: $name";
        }
        if($uploaded>0){setFlash('success',"$uploaded foto berhasil diupload!");redirect(SITE_URL.'/admin/galeri.php');}
        else $error=implode('<br>',$errors)?:'Tidak ada file yang berhasil diupload.';
    } else $error='Pilih minimal 1 foto!';
}
include 'header.php';
?>
<div class="page-title"><div><h1>Upload Foto Galeri</h1></div><a href="galeri.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<?php if(!empty($error)):?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?=$error?></div><?php endif;?>
<div class="form-card">
    <div class="form-card-header"><i class="fas fa-images"></i> Form Upload Foto Galeri</div>
    <div class="form-card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Judul / Nama Kegiatan</label>
                <input type="text" name="judul" class="form-control" value="<?=e($_POST['judul']??'')?>" placeholder="Nama kegiatan (opsional, untuk semua foto)">
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"><?=e($_POST['keterangan']??'')?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Pilih Foto <span>*</span></label>
                <input type="file" name="gambar[]" class="form-control" accept="image/*" multiple required
                       onchange="previewMultiple(this)">
                <p class="form-hint">Bisa pilih beberapa foto sekaligus. Format JPG/PNG/WEBP, maks 5MB per foto.</p>
                <div id="multiPreview" style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-upload"></i> Upload Foto</button>
                <a href="galeri.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
function previewMultiple(input){
    const container=document.getElementById('multiPreview');
    container.innerHTML='';
    Array.from(input.files).forEach(file=>{
        const reader=new FileReader();
        reader.onload=e=>{
            const div=document.createElement('div');
            div.style.cssText='position:relative;width:120px;height:100px;border-radius:8px;overflow:hidden;border:2px solid var(--border)';
            div.innerHTML=`<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
            div.innerHTML+=`<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.6);color:#fff;font-size:0.65rem;padding:3px 5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${file.name}</div>`;
            container.appendChild(div);
        };reader.readAsDataURL(file);
    });
}
</script>
<?php include 'footer.php'; ?>
