<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Manajemen Kelurahan';
$list = $conn->query("SELECT * FROM kelurahan ORDER BY nama");
include 'header.php';
?>
<div class="page-title">
    <div><h1>Manajemen Kelurahan / RW</h1><p>Data wilayah Kelurahan Pulomerak</p></div>
    <a href="kelurahan-add.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Kelurahan</a>
</div>
<div class="table-card">
    <div class="table-card-header"><h3><i class="fas fa-city" style="color:var(--p)"></i> Daftar Kelurahan / RW</h3></div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr><th>#</th><th>Gambar</th><th>Nama</th><th>RW</th><th>RT</th><th>Penduduk</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if ($list->num_rows === 0): ?>
                <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--gray)">Belum ada data kelurahan.</td></tr>
            <?php else: $no = 1; while ($k = $list->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><img src="<?= getImg($k['gambar'], 'kegiatan') ?>" class="table-img" alt=""></td>
                    <td class="table-title">
                        <?= e($k['nama']) ?>
                        <?php if (!empty($k['inovasi'])): ?>
                            <p><i class="fas fa-lightbulb"></i> Ada program inovasi</p>
                        <?php endif; ?>
                    </td>
                    <td><?= e($k['jumlah_rw'] ?? '-') ?></td>
                    <td><?= e($k['jumlah_rt'] ?? '-') ?></td>
                    <td><?= !empty($k['penduduk']) ? number_format($k['penduduk']) . ' jiwa' : '-' ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../kelurahan-detail.php?id=<?= $k['id'] ?>" class="btn-edit" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="kelurahan-edit.php?id=<?= $k['id'] ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="kelurahan-delete.php?id=<?= $k['id'] ?>" class="btn-delete"
                               data-confirm="Yakin hapus data kelurahan ini?">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
