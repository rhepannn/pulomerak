<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

$pageTitle = 'Kelola Bidang';
$stmt = $conn->query("SELECT id, nama, slug, (SELECT COUNT(*) FROM anggota_bidang WHERE bidang_id = bidang.id) as total_anggota FROM bidang ORDER BY urutan ASC");

include 'header.php';
?>

<div class="page-title">
    <div><h1>Kelola Bidang & POKJA</h1></div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-sitemap"></i> Daftar Bidang & POKJA</div>
    <div class="card-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Nama Bidang</th>
                    <th>Slug</th>
                    <th width="120">Anggota</th>
                    <th width="280">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($b = $stmt->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= e($b['nama']) ?></strong></td>
                    <td><code><?= e($b['slug']) ?></code></td>
                    <td><span class="badge badge-info"><?= $b['total_anggota'] ?> Orang</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="bidang-edit.php?id=<?= $b['id'] ?>" class="btn-action btn-edit" title="Edit Bidang">
                                <i class="fas fa-edit"></i> Edit Bidang
                            </a>
                            <a href="anggota.php?bidang_id=<?= $b['id'] ?>" class="btn-action btn-add" style="background:var(--accent)" title="Kelola Anggota">
                                <i class="fas fa-users"></i> Anggota
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
