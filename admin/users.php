<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

if (!isSuperAdmin()) {
    setFlash('error', 'Halaman ini hanya dapat diakses oleh Superadmin.');
    redirect(SITE_URL . '/admin/index.php');
}

$pageTitle = 'Manajemen User';

// Ambil semua user beserta nama kelurahannya
$sql = "SELECT u.*, k.nama as nama_kelurahan 
        FROM users u 
        LEFT JOIN kelurahan k ON u.kelurahan_id = k.id 
        ORDER BY u.role, u.username";
$list = $conn->query($sql);

include 'header.php';
?>

<div class="page-title">
    <div>
        <h1>Manajemen User</h1>
        <p>Kelola akun admin kecamatan dan kelurahan</p>
    </div>
    <a href="users-add.php" class="btn-add"><i class="fas fa-user-plus"></i> Tambah User</a>
</div>

<div class="table-card">
    <div class="table-card-header">
        <h3><i class="fas fa-users" style="color:var(--p)"></i> Daftar Pengguna</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Penugasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($list->num_rows === 0): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray)">Belum ada user tambahan.</td></tr>
                <?php else: $no = 1; while ($u = $list->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= e($u['username']) ?></strong></td>
                        <td><?= e($u['nama']) ?></td>
                        <td>
                            <?php if ($u['kelurahan_id'] === null): ?>
                                <span class="badge badge-primary">Admin Kecamatan</span>
                            <?php else: ?>
                                <span class="badge badge-info">Admin Kelurahan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u['kelurahan_id'] === null): ?>
                                <span style="color:var(--gray-500); font-style:italic">Seluruh Wilayah</span>
                            <?php else: ?>
                                <i class="fas fa-city" style="color:var(--primary); font-size:0.8rem"></i> <?= e($u['nama_kelurahan']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="users-edit.php?id=<?= $u['id'] ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <?php if ($u['id'] != $_SESSION['admin_id']): // Jangan hapus diri sendiri ?>
                                    <a href="users-delete.php?id=<?= $u['id'] ?>" class="btn-delete"
                                       data-confirm="Yakin ingin menghapus user '<?= e($u['username']) ?>'?">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
