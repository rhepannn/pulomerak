<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$bidang_id = (int)($_GET['bidang_id'] ?? 0);
if (!$bidang_id) redirect(SITE_URL.'/admin/bidang.php');

$stmt = $conn->prepare("SELECT * FROM bidang WHERE id = ?");
$stmt->bind_param('i', $bidang_id);
$stmt->execute();
$bidang = $stmt->get_result()->fetch_assoc();

if (!$bidang) redirect(SITE_URL.'/admin/bidang.php');

$pageTitle = 'Kelola Anggota: ' . $bidang['nama'];

$stmt = $conn->prepare("SELECT * FROM anggota_bidang WHERE bidang_id = ? ORDER BY urutan ASC");
$stmt->bind_param('i', $bidang_id);
$stmt->execute();
$list = $stmt->get_result();

include 'header.php';
?>

<div class="page-title">
    <div>
        <h1>Kelola Anggota</h1>
        <p style="color: var(--text-secondary); margin-top: 5px;">Bidang: <strong><?= e($bidang['nama']) ?></strong></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="bidang.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="anggota-add.php?bidang_id=<?= $bidang_id ?>" class="btn-add"><i class="fas fa-plus-circle"></i> Tambah Anggota</a>
    </div>
</div>


<div class="card">
    <div class="card-header"><i class="fas fa-users"></i> Daftar Pengurus & Anggota</div>
    <div class="card-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th width="80">Foto</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>No. HP / WA</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($list->num_rows === 0): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-secondary);">Belum ada data anggota di bidang ini.</td></tr>
                <?php else:
                    while($a = $list->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <div class="table-img">
                            <img src="<?= getImg($a['foto'], 'bidang') ?>" alt="">
                        </div>
                    </td>
                    <td><strong><?= e($a['nama']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e($a['jabatan']) ?></span></td>
                    <td><?= e($a['no_hp'] ?: '-') ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="anggota-edit.php?id=<?= $a['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="anggota-delete.php?id=<?= $a['id'] ?>" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus anggota ini?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
