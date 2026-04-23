<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

if (!isSuperAdmin()) {
    setFlash('error', 'Halaman ini hanya dapat diakses oleh Superadmin.');
    redirect(SITE_URL . '/admin/index.php');
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(SITE_URL . '/admin/users.php');

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();
if (!$u) redirect(SITE_URL . '/admin/users.php');

$pageTitle = 'Edit User';
$kels = $conn->query("SELECT id, nama FROM kelurahan ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama         = trim($_POST['nama'] ?? '');
    $password     = $_POST['password'] ?? '';
    $role_type    = $_POST['role_type'] ?? 'kecamatan';
    $kelurahan_id = ($role_type === 'kelurahan') ? (int)$_POST['kelurahan_id'] : null;

    if (empty($nama)) {
        $error = 'Nama lengkap wajib diisi!';
    } else {
        $role_text = ($role_type === 'kecamatan') ? 'admin' : 'kelurahan_admin';
        
        if (!empty($password)) {
            // Update dengan password baru
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET nama=?, password=?, role=?, kelurahan_id=? WHERE id=?");
            $stmt->bind_param('ssssi', $nama, $pass_hash, $role_text, $kelurahan_id, $id);
        } else {
            // Update tanpa ganti password
            $stmt = $conn->prepare("UPDATE users SET nama=?, role=?, kelurahan_id=? WHERE id=?");
            $stmt->bind_param('sssi', $nama, $role_text, $kelurahan_id, $id);
        }
        
        if ($stmt->execute()) {
            setFlash('success', 'Data user berhasil diperbarui!');
            redirect(SITE_URL . '/admin/users.php');
        } else {
            $error = 'Gagal menyimpan: ' . $conn->error;
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Edit User Admin</h1></div>
    <a href="users.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
<?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-user-edit"></i> Edit Akun: <?= e($u['username']) ?></div>
    <div class="form-card-body">
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="<?= e($u['username']) ?>" disabled style="background:#f1f1f1">
                    <p class="form-hint">Username tidak dapat diubah.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? $u['nama']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ganti Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti password">
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Role <span>*</span></label>
                    <?php 
                        $curr_role = ($_POST['role_type'] ?? ($u['kelurahan_id'] === null ? 'kecamatan' : 'kelurahan'));
                    ?>
                    <select name="role_type" id="roleType" class="form-control" onchange="toggleKelurahan()">
                        <option value="kecamatan" <?= $curr_role === 'kecamatan' ? 'selected' : '' ?>>Admin Kecamatan (Superadmin)</option>
                        <option value="kelurahan" <?= $curr_role === 'kelurahan' ? 'selected' : '' ?>>Admin Kelurahan</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="kelurahanGroup" style="display: <?= $curr_role === 'kelurahan' ? 'block' : 'none' ?>;">
                <label class="form-label">Tugaskan ke Kelurahan <span>*</span></label>
                <select name="kelurahan_id" class="form-control">
                    <option value="">-- Pilih Kelurahan --</option>
                    <?php while ($k = $kels->fetch_assoc()): ?>
                        <option value="<?= $k['id'] ?>" <?= (($_POST['kelurahan_id'] ?? $u['kelurahan_id']) == $k['id']) ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="users.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleKelurahan() {
    const role = document.getElementById('roleType').value;
    const group = document.getElementById('kelurahanGroup');
    group.style.display = (role === 'kelurahan') ? 'block' : 'none';
}
</script>

<?php include 'footer.php'; ?>
