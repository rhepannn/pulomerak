<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Manajemen Kegiatan';

$page = max(1,(int)($_GET['page']??1)); $perPage=15; $offset=($page-1)*$perPage;
$kelId   = getKelurahanId();
$where   = isSuperAdmin() ? "" : " WHERE kelurahan_id = " . (int)$kelId;

$totalQuery = $conn->query("SELECT COUNT(*) FROM kegiatan" . $where);
$total      = $totalQuery->fetchColumn();

$stmt  = $conn->prepare("SELECT * FROM kegiatan" . $where . " ORDER BY tgl_kegiatan DESC LIMIT ? OFFSET ?");
$stmt->execute([$perPage, $offset]);
$list  = $stmt->fetchAll();
include 'header.php';
?>
<div class="page-title">
    <div><h1>Manajemen Kegiatan</h1><p>Total: <?=$total?> kegiatan</p></div>
    <a href="kegiatan-add.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Kegiatan</a>
</div>
<div class="table-card">
    <div class="table-card-header"><h3><i class="fas fa-calendar-check" style="color:var(--p)"></i> Daftar Kegiatan</h3></div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>#</th><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Lokasi</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($list)): ?>
                <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--gray)">Belum ada kegiatan. <a href="kegiatan-add.php" style="color:var(--p);font-weight:700">Tambah sekarang</a></td></tr>
            <?php else: $no=$offset+1; foreach ($list as $k): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><img src="<?=getImg($k['gambar'],'kegiatan')?>" class="table-img" alt=""></td>
                    <td class="table-title"><?=e($k['judul'])?></td>
                    <td><span class="badge badge-success"><?=e($k['kategori']??'-')?></span></td>
                    <td style="white-space:nowrap;font-size:0.82rem"><?=formatTanggal($k['tgl_kegiatan'])?></td>
                    <td style="font-size:0.82rem"><?=e($k['lokasi']??'-')?></td>
                    <td>
                        <div class="action-btns">
                            <a href="kegiatan-edit.php?id=<?=$k['id']?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="kegiatan-delete.php?id=<?=$k['id']?>" class="btn-delete" data-confirm="Yakin hapus kegiatan ini?"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?=paginate($total,$page,$perPage,'kegiatan.php?')?>
<?php include 'footer.php'; ?>
