<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Manajemen Berita';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset  = ($page - 1) * $perPage;

$kelId   = getKelurahanId();
$where   = isSuperAdmin() ? "" : " WHERE kelurahan_id = " . (int)$kelId;

$total   = $conn->query("SELECT COUNT(*) FROM berita" . $where)->fetch_row()[0];
$sql     = "SELECT * FROM berita" . $where . " ORDER BY tgl_post DESC LIMIT ? OFFSET ?";
$stmt    = $conn->prepare($sql);
$stmt->bind_param('ii', $perPage, $offset);
$stmt->execute();
$list    = $stmt->get_result();

include 'header.php';
?>

<div class="page-title">
    <div>
        <h1>Manajemen Berita</h1>
        <p>Total: <?= $total ?> berita</p>
    </div>
    <a href="berita-add.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Berita</a>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h3><i class="fas fa-newspaper" style="color:var(--p)"></i> Daftar Berita</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list->num_rows === 0): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray)">Belum ada berita. <a href="berita-add.php" style="color:var(--p);font-weight:700">Tambah sekarang</a></td></tr>
                <?php else: ?>
                    <?php $no = $offset + 1; while ($b = $list->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <img src="<?= getImg($b['gambar'], 'berita') ?>" class="table-img" alt="">
                            </td>
                            <td class="table-title">
                                <?= e($b['judul']) ?>
                                <?php if (!empty($b['url_sumber'])): ?>
                                    <p><i class="fas fa-link"></i> Ada link sumber</p>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-info"><?= e($b['kategori'] ?? '-') ?></span></td>
                            <td style="white-space:nowrap;font-size:0.82rem"><?= formatTanggal($b['tgl_post']) ?></td>
                            <td>
                                <div class="action-btns">
                                    <a href="../berita-detail.php?id=<?= $b['id'] ?>" class="btn-edit" target="_blank" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="berita-edit.php?id=<?= $b['id'] ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="berita-delete.php?id=<?= $b['id'] ?>" class="btn-delete"
                                       data-confirm="Yakin hapus berita '<?= e(addslashes($b['judul'])) ?>'?">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo paginate($total, $page, $perPage, 'berita.php?'); ?>

<?php include 'footer.php'; ?>
