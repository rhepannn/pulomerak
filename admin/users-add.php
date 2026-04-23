<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();

if (!isSuperAdmin()) {
    setFlash('error', 'Halaman ini hanya dapat diakses oleh Superadmin.');
    redirect(SITE_URL . '/admin/index.php');
}

$pageTitle = 'Tambah User';
$kels = $conn->query("SELECT id, nama FROM kelurahan ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = trim($_POST['username'] ?? '');
    $nama         = trim($_POST['nama'] ?? '');
    $password     = $_POST['password'] ?? '';
    $role_type    = $_POST['role_type'] ?? 'kecamatan'; // kecamatan | kelurahan
    $kelurahan_id = ($role_type === 'kelurahan') ? (int)$_POST['kelurahan_id'] : null;

    if (empty($username) || empty($nama) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Cek username unik
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param('s', $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $role_text = ($role_type === 'kecamatan') ? 'admin' : 'kelurahan_admin';
            
            $stmt = $conn->prepare("INSERT INTO users (username, password, nama, role, kelurahan_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssi', $username, $pass_hash, $nama, $role_text, $kelurahan_id);
            
            if ($stmt->execute()) {
                setFlash('success', 'User baru berhasil ditambahkan!');
                redirect(SITE_URL . '/admin/users.php');
            } else {
                $error = 'Gagal menyimpan: ' . $conn->error;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div><h1>Tambah User Admin</h1></div>
    <a href="users.php" class="btn-add" style="background:var(--gray)"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= e($error) ?></div>
<?php endif; ?>

<div class="form-card">
    <div class="form-card-header"><i class="fas fa-user-plus"></i> Form Tambah User</div>
    <div class="form-card-body">
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username <span>*</span></label>
                    <input type="text" name="username" class="form-control" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="Contoh: admintamansari">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span>*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($_POST['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap...">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password <span>*</span></label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password minimal 6 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Role <span>*</span></label>
                    <select name="role_type" id="roleType" class="form-control" onchange="toggleKelurahan()">
                        <option value="kecamatan" <?= (($_POST['role_type'] ?? '') === 'kecamatan') ? 'selected' : '' ?>>Admin Kecamatan (Superadmin)</option>
                        <option value="kelurahan" <?= (($_POST['role_type'] ?? '') === 'kelurahan') ? 'selected' : '' ?>>Admin Kelurahan</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="kelurahanGroup" style="display: <?= (($_POST['role_type'] ?? '') === 'kelurahan') ? 'block' : 'none' ?>;">
                <label class="form-label">Tugaskan ke Kelurahan <span>*</span></label>
                <select name="kelurahan_id" class="form-control">
                    <option value="">-- Pilih Kelurahan --</option>
                    <?php while ($k = $kels->fetch_assoc()): ?>
                        <option value="<?= $k['id'] ?>" <?= (($_POST['kelurahan_id'] ?? 0) == $k['id']) ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                    <?php endwhile; ?>
                </select>
                <p class="form-hint">User ini hanya bisa mengelola data untuk kelurahan yang dipilih.</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Buat User Baru</button>
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
