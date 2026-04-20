<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Perpustakaan Digital';
$page=max(1,(int)($_GET['page']??1));$perPage=15;$offset=($page-1)*$perPage;
$total=$conn->query("SELECT COUNT(*) FROM perpustakaan")->fetch_row()[0];
$stmt=$conn->prepare("SELECT * FROM perpustakaan ORDER BY tgl_upload DESC LIMIT ? OFFSET ?");
$stmt->bind_param('ii',$perPage,$offset);$stmt->execute();$list=$stmt->get_result();
include 'header.php';
?>
<div class="page-title">
    <div><h1>Perpustakaan Digital</h1><p>Total: <?=$total?> dokumen</p></div>
    <a href="perpustakaan-add.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Dokumen</a>
</div>
<div class="table-card">
    <div class="table-card-header"><h3><i class="fas fa-book-open" style="color:var(--p)"></i> Daftar Dokumen</h3></div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>File</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if($list->num_rows===0): ?>
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray)">Belum ada dokumen.</td></tr>
            <?php else: $no=$offset+1;while($p=$list->fetch_assoc()):
                $ext=strtolower(pathinfo($p['file']??'',PATHINFO_EXTENSION)); ?>
                <tr>
                    <td><?=$no++?></td>
                    <td class="table-title"><?=e($p['judul'])?><p><?=e(truncate($p['deskripsi']??'',50))?></p></td>
                    <td><span class="badge badge-info"><?=e($p['kategori']??'-')?></span></td>
                    <td style="white-space:nowrap;font-size:0.82rem"><?=formatTanggal($p['tgl_upload'])?></td>
                    <td>
                        <?php if(!empty($p['file'])): ?>
                            <span class="badge <?=$ext==='pdf'?'badge-danger':'badge-success'?>"><?=strtoupper($ext)?></span>
                        <?php else: ?><span class="badge badge-warning">-</span><?php endif; ?>
                    </td>
                    <td>
                        <div class="action-btns">
                            <?php if(!empty($p['file'])): ?>
                                <a href="<?=SITE_URL?>/uploads/perpustakaan/<?=e($p['file'])?>" class="btn-green-sm" download><i class="fas fa-download"></i></a>
                            <?php endif; ?>
                            <a href="perpustakaan-edit.php?id=<?=$p['id']?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="perpustakaan-delete.php?id=<?=$p['id']?>" class="btn-delete" data-confirm="Yakin hapus dokumen ini?"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile;endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?=paginate($total,$page,$perPage,'perpustakaan.php?')?>
<?php include 'footer.php'; ?>
