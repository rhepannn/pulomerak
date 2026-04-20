<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle='Manajemen Dinamika Masyarakat';
$page=max(1,(int)($_GET['page']??1));$perPage=15;$offset=($page-1)*$perPage;
$total=$conn->query("SELECT COUNT(*) FROM dinamika")->fetch_row()[0];
$stmt=$conn->prepare("SELECT * FROM dinamika ORDER BY tgl_post DESC LIMIT ? OFFSET ?");
$stmt->bind_param('ii',$perPage,$offset);$stmt->execute();$list=$stmt->get_result();
include 'header.php';
?>
<div class="page-title">
    <div><h1>Dinamika Masyarakat</h1><p>Total: <?=$total?> artikel</p></div>
    <a href="dinamika-add.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Artikel</a>
</div>
<div class="table-card">
    <div class="table-card-header"><h3><i class="fas fa-users" style="color:var(--p)"></i> Daftar Artikel Dinamika</h3></div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>#</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if($list->num_rows===0): ?>
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray)">Belum ada artikel.</td></tr>
            <?php else: $no=$offset+1;while($d=$list->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td class="table-title"><?=e($d['judul'])?><p><?=e(truncate($d['isi'],50))?></p></td>
                    <td style="font-size:0.82rem"><?=e($d['penulis']??'Admin')?></td>
                    <td><span class="badge badge-info"><?=e($d['kategori']??'-')?></span></td>
                    <td style="white-space:nowrap;font-size:0.82rem"><?=formatTanggal($d['tgl_post'])?></td>
                    <td>
                        <div class="action-btns">
                            <a href="dinamika-edit.php?id=<?=$d['id']?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="dinamika-delete.php?id=<?=$d['id']?>" class="btn-delete" data-confirm="Yakin hapus artikel ini?"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile;endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?=paginate($total,$page,$perPage,'dinamika.php?')?>
<?php include 'footer.php'; ?>
