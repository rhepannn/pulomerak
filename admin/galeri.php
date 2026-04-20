<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Manajemen Galeri';
$page=max(1,(int)($_GET['page']??1));$perPage=20;$offset=($page-1)*$perPage;
$total=$conn->query("SELECT COUNT(*) FROM galeri")->fetch_row()[0];
$stmt=$conn->prepare("SELECT * FROM galeri ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param('ii',$perPage,$offset);$stmt->execute();$list=$stmt->get_result();
include 'header.php';
?>
<div class="page-title">
    <div><h1>Manajemen Galeri</h1><p>Total: <?=$total?> foto</p></div>
    <a href="galeri-add.php" class="btn-add"><i class="fas fa-plus"></i> Upload Foto</a>
</div>
<div class="table-card">
    <div class="table-card-header"><h3><i class="fas fa-images" style="color:var(--p)"></i> Galeri Foto</h3></div>
    <div style="padding:20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
        <?php if($list->num_rows===0): ?>
            <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray)">Belum ada foto.</div>
        <?php else: while($g=$list->fetch_assoc()): ?>
            <div style="background:var(--light);border-radius:12px;overflow:hidden;border:1px solid var(--border);transition:all 0.2s"
                 onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow=''">
                <div style="aspect-ratio:4/3;overflow:hidden;background:#ddd">
                    <img src="<?=getImg($g['gambar'],'galeri')?>" alt="<?=e($g['judul'])?>" style="width:100%;height:100%;object-fit:cover;display:block;">
                </div>
                <div style="padding:10px">
                    <div style="font-size:0.82rem;font-weight:700;color:var(--dark);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?=e($g['judul']??'Tanpa judul')?></div>
                    <div style="font-size:0.72rem;color:var(--gray);margin-bottom:10px"><?=formatTanggal($g['created_at'])?></div>
                    <a href="galeri-delete.php?id=<?=$g['id']?>" class="btn-delete" style="width:100%;justify-content:center;font-size:0.78rem"
                       data-confirm="Yakin hapus foto ini?"><i class="fas fa-trash"></i> Hapus</a>
                </div>
            </div>
        <?php endwhile;endif; ?>
    </div>
</div>
<?=paginate($total,$page,$perPage,'galeri.php?')?>
<?php include 'footer.php'; ?>
