<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/kelurahan.php');
$stmt=$conn->prepare("SELECT * FROM kelurahan WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$k=$stmt->get_result()->fetch_assoc();if(!$k)redirect(SITE_URL.'/admin/kelurahan.php');

// CEK AKSES
checkOwnership($k['id']);
$pageTitle='Edit Kelurahan';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama=trim($_POST['nama']??'');$ketua_pkk=trim($_POST['ketua_pkk']??'');$deskripsi=$_POST['deskripsi']??'';
    $inovasi=$_POST['inovasi']??'';
    $jumlah_rw=(int)($_POST['jumlah_rw']??0);
    $jumlah_rt=(int)($_POST['jumlah_rt']??0);
    $penduduk=(int)($_POST['penduduk']??0);
    $jumlah_link=(int)($_POST['jumlah_link']??0);
    $dasa_wisma=(int)($_POST['dasa_wisma']??0);
    $ibu_hamil=(int)($_POST['ibu_hamil']??0);
    $ibu_melahirkan=(int)($_POST['ibu_melahirkan']??0);
    $ibu_nifas=(int)($_POST['ibu_nifas']??0);
    $ibu_meninggal=(int)($_POST['ibu_meninggal']??0);
    $bayi_lahir_l=(int)($_POST['bayi_lahir_l']??0);
    $bayi_lahir_p=(int)($_POST['bayi_lahir_p']??0);
    $akte_ada=(int)($_POST['akte_ada']??0);
    $akte_tidak=(int)($_POST['akte_tidak']??0);
    $bayi_meninggal_l=(int)($_POST['bayi_meninggal_l']??0);
    $bayi_meninggal_p=(int)($_POST['bayi_meninggal_p']??0);
    $balita_meninggal_l=(int)($_POST['balita_meninggal_l']??0);
    $balita_meninggal_p=(int)($_POST['balita_meninggal_p']??0);

    $sekretaris_pkk=trim($_POST['sekretaris_pkk']??'');
    $bendahara_pkk=trim($_POST['bendahara_pkk']??'');
    $pokja1_pkk=trim($_POST['pokja1_pkk']??'');
    $pokja2_pkk=trim($_POST['pokja2_pkk']??'');
    $pokja3_pkk=trim($_POST['pokja3_pkk']??'');
    $pokja4_pkk=trim($_POST['pokja4_pkk']??'');

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
        $stmt=$conn->prepare("UPDATE kelurahan SET 
            nama=?, ketua_pkk=?, sekretaris_pkk=?, bendahara_pkk=?, 
            pokja1_pkk=?, pokja2_pkk=?, pokja3_pkk=?, pokja4_pkk=?,
            deskripsi=?, inovasi=?, gambar=?, 
            jumlah_rw=?, jumlah_rt=?, penduduk=?, jumlah_link=?, dasa_wisma=?,
            ibu_hamil=?, ibu_melahirkan=?, ibu_nifas=?, ibu_meninggal=?,
            bayi_lahir_l=?, bayi_lahir_p=?, akte_ada=?, akte_tidak=?,
            bayi_meninggal_l=?, bayi_meninggal_p=?, balita_meninggal_l=?, balita_meninggal_p=?
            WHERE id=?");
        $stmt->bind_param('ssssssssssiiiiiiiiiiiiiiiiii', 
            $nama, $ketua_pkk, $sekretaris_pkk, $bendahara_pkk,
            $pokja1_pkk, $pokja2_pkk, $pokja3_pkk, $pokja4_pkk,
            $deskripsi, $inovasi, $gambar,
            $jumlah_rw, $jumlah_rt, $penduduk, $jumlah_link, $dasa_wisma,
            $ibu_hamil, $ibu_melahirkan, $ibu_nifas, $ibu_meninggal,
            $bayi_lahir_l, $bayi_lahir_p, $akte_ada, $akte_tidak,
            $bayi_meninggal_l, $bayi_meninggal_p, $balita_meninggal_l, $balita_meninggal_p,
            $id);
        
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
            <div style="background:var(--gray-50); padding:20px; border-radius:8px; margin-bottom:25px; border:1px solid var(--border)">
                <h3 style="font-size:0.9rem; color:var(--primary); margin-bottom:15px; text-transform:uppercase; letter-spacing:0.5px">Informasi Dasar & Pengurus PKK</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Kelurahan / RW <span>*</span></label>
                        <input type="text" name="nama" class="form-control" required value="<?=e($_POST['nama']??$k['nama'])?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Ketua TP PKK</label>
                        <input type="text" name="ketua_pkk" class="form-control" value="<?=e($_POST['ketua_pkk']??$k['ketua_pkk']??'')?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Sekretaris PKK</label>
                        <input type="text" name="sekretaris_pkk" class="form-control" value="<?=e($_POST['sekretaris_pkk']??$k['sekretaris_pkk']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Bendahara PKK</label>
                        <input type="text" name="bendahara_pkk" class="form-control" value="<?=e($_POST['bendahara_pkk']??$k['bendahara_pkk']??'')?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ketua Pokja I</label>
                        <input type="text" name="pokja1_pkk" class="form-control" value="<?=e($_POST['pokja1_pkk']??$k['pokja1_pkk']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ketua Pokja II</label>
                        <input type="text" name="pokja2_pkk" class="form-control" value="<?=e($_POST['pokja2_pkk']??$k['pokja2_pkk']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ketua Pokja III</label>
                        <input type="text" name="pokja3_pkk" class="form-control" value="<?=e($_POST['pokja3_pkk']??$k['pokja3_pkk']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ketua Pokja IV</label>
                        <input type="text" name="pokja4_pkk" class="form-control" value="<?=e($_POST['pokja4_pkk']??$k['pokja4_pkk']??'')?>">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Foto Wilayah</label>
                    <?php if(!empty($k['gambar'])): ?>
                        <img src="<?=getImg($k['gambar'],'kegiatan')?>" id="prevImg" class="current-img" alt="">
                    <?php else: ?>
                        <img id="prevImg" src="" class="current-img" style="display:none" alt="">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control" accept="image/*" data-preview="prevImg" style="margin-top:8px">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Wilayah</label>
                    <textarea name="deskripsi" class="form-control" rows="5"><?=e($_POST['deskripsi']??$k['deskripsi'])?></textarea>
                </div>
            </div>

            <div style="background:var(--gray-50); padding:20px; border-radius:8px; margin-bottom:25px; border:1px solid var(--border)">
                <h3 style="font-size:0.9rem; color:var(--primary); margin-bottom:15px; text-transform:uppercase; letter-spacing:0.5px">Data Wilayah & Kependudukan</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah RW</label>
                        <input type="number" name="jumlah_rw" class="form-control" value="<?=e($_POST['jumlah_rw']??$k['jumlah_rw']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah RT</label>
                        <input type="number" name="jumlah_rt" class="form-control" value="<?=e($_POST['jumlah_rt']??$k['jumlah_rt']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Link</label>
                        <input type="number" name="jumlah_link" class="form-control" value="<?=e($_POST['jumlah_link']??$k['jumlah_link']??0)?>" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Dasa Wisma</label>
                        <input type="number" name="dasa_wisma" class="form-control" value="<?=e($_POST['dasa_wisma']??$k['dasa_wisma']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Penduduk (Jiwa)</label>
                        <input type="number" name="penduduk" class="form-control" value="<?=e($_POST['penduduk']??$k['penduduk']??0)?>" min="0">
                    </div>
                </div>
            </div>

            <div style="background:var(--gray-50); padding:20px; border-radius:8px; margin-bottom:25px; border:1px solid var(--border)">
                <h3 style="font-size:0.9rem; color:var(--primary); margin-bottom:15px; text-transform:uppercase; letter-spacing:0.5px">Data Ibu & Bayi</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ibu Hamil</label>
                        <input type="number" name="ibu_hamil" class="form-control" value="<?=e($_POST['ibu_hamil']??$k['ibu_hamil']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ibu Melahirkan</label>
                        <input type="number" name="ibu_melahirkan" class="form-control" value="<?=e($_POST['ibu_melahirkan']??$k['ibu_melahirkan']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ibu Nifas</label>
                        <input type="number" name="ibu_nifas" class="form-control" value="<?=e($_POST['ibu_nifas']??$k['ibu_nifas']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ibu Meninggal</label>
                        <input type="number" name="ibu_meninggal" class="form-control" value="<?=e($_POST['ibu_meninggal']??$k['ibu_meninggal']??0)?>" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bayi Lahir (Laki-laki)</label>
                        <input type="number" name="bayi_lahir_l" class="form-control" value="<?=e($_POST['bayi_lahir_l']??$k['bayi_lahir_l']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bayi Lahir (Perempuan)</label>
                        <input type="number" name="bayi_lahir_p" class="form-control" value="<?=e($_POST['bayi_lahir_p']??$k['bayi_lahir_p']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Akte Ada</label>
                        <input type="number" name="akte_ada" class="form-control" value="<?=e($_POST['akte_ada']??$k['akte_ada']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Akte Tidak</label>
                        <input type="number" name="akte_tidak" class="form-control" value="<?=e($_POST['akte_tidak']??$k['akte_tidak']??0)?>" min="0">
                    </div>
                </div>
            </div>

            <div style="background:var(--gray-50); padding:20px; border-radius:8px; margin-bottom:25px; border:1px solid var(--border)">
                <h3 style="font-size:0.9rem; color:var(--primary); margin-bottom:15px; text-transform:uppercase; letter-spacing:0.5px">Data Kematian</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bayi Meninggal (L)</label>
                        <input type="number" name="bayi_meninggal_l" class="form-control" value="<?=e($_POST['bayi_meninggal_l']??$k['bayi_meninggal_l']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bayi Meninggal (P)</label>
                        <input type="number" name="bayi_meninggal_p" class="form-control" value="<?=e($_POST['bayi_meninggal_p']??$k['bayi_meninggal_p']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Balita Meninggal (L)</label>
                        <input type="number" name="balita_meninggal_l" class="form-control" value="<?=e($_POST['balita_meninggal_l']??$k['balita_meninggal_l']??0)?>" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Balita Meninggal (P)</label>
                        <input type="number" name="balita_meninggal_p" class="form-control" value="<?=e($_POST['balita_meninggal_p']??$k['balita_meninggal_p']??0)?>" min="0">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Inovasi Unggulan (Teks)</label>
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
