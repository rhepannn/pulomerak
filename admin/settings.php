<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Pengaturan Akun';

$adminId = $_SESSION['admin_id'];
$errors  = [];
$success = '';

// Ambil data user saat ini
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ── PROSES FORM ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // ──── GANTI PROFIL (nama & username) ────────────────────
    if ($action === 'profil') {
        $nama     = trim($_POST['nama'] ?? '');
        $username = trim($_POST['username'] ?? '');

        if (empty($nama))     $errors[] = 'Nama tidak boleh kosong.';
        if (empty($username)) $errors[] = 'Username tidak boleh kosong.';
        if (!preg_match('/^[a-zA-Z0-9_]{3,60}$/', $username))
            $errors[] = 'Username hanya boleh huruf, angka, underscore (3-60 karakter).';

        // Cek username duplikat (selain user sendiri)
        if (empty($errors)) {
            $chk = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $chk->bind_param("si", $username, $adminId);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0)
                $errors[] = 'Username sudah digunakan, pilih yang lain.';
            $chk->close();
        }

        if (empty($errors)) {
            $upd = $conn->prepare("UPDATE users SET nama = ?, username = ? WHERE id = ?");
            $upd->bind_param("ssi", $nama, $username, $adminId);
            $upd->execute();
            $upd->close();

            // Update session
            $_SESSION['admin_name']     = $nama;
            $_SESSION['admin_username'] = $username;

            setFlash('success', 'Profil berhasil diperbarui!');
            header('Location: settings.php');
            exit;
        }
    }

    // ──── GANTI PASSWORD ────────────────────────────────────
    if ($action === 'password') {
        $passLama  = $_POST['pass_lama']   ?? '';
        $passBaru  = $_POST['pass_baru']   ?? '';
        $passUlang = $_POST['pass_ulang']  ?? '';

        if (empty($passLama))  $errors[] = 'Password lama wajib diisi.';
        if (strlen($passBaru) < 6)
            $errors[] = 'Password baru minimal 6 karakter.';
        if ($passBaru !== $passUlang)
            $errors[] = 'Konfirmasi password tidak cocok.';

        if (empty($errors)) {
            // Verifikasi password lama
            if (!password_verify($passLama, $user['password'])) {
                $errors[] = 'Password lama salah.';
            } else {
                $hash = password_hash($passBaru, PASSWORD_DEFAULT);
                $upd  = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param("si", $hash, $adminId);
                $upd->execute();
                $upd->close();

                setFlash('success', 'Password berhasil diubah. Silakan login ulang.');
                session_destroy();
                header('Location: login.php');
                exit;
            }
        }
    }
}

include 'header.php';
?>

<div class="page-title">
    <div>
        <h1><i class="fas fa-cog" style="color:var(--p)"></i> Pengaturan Akun</h1>
        <p>Kelola username, nama tampilan, dan password Anda</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error" style="margin-bottom:20px">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="margin:0;padding-left:18px">
            <?php foreach ($errors as $e): ?>
                <li><?= e($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start;">

    <!-- ── FORM PROFIL ──────────────────────────────────── -->
    <div class="table-card">
        <div class="table-card-header">
            <h3><i class="fas fa-user-edit" style="color:var(--p)"></i> Ubah Profil</h3>
        </div>
        <form method="POST" style="padding:24px;display:flex;flex-direction:column;gap:18px;">
            <input type="hidden" name="action" value="profil">

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:red">*</span></label>
                <input type="text" name="nama" class="form-control"
                       value="<?= e($_POST['nama'] ?? $user['nama']) ?>"
                       placeholder="Nama tampilan admin" required>
            </div>

            <div class="form-group">
                <label class="form-label">Username <span style="color:red">*</span></label>
                <input type="text" name="username" class="form-control"
                       value="<?= e($_POST['username'] ?? $user['username']) ?>"
                       placeholder="Username untuk login" required>
                <small style="color:var(--gray);font-size:0.78rem;margin-top:5px;display:block">
                    Gunakan huruf, angka, dan underscore (min. 3 karakter)
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <input type="text" class="form-control"
                       value="<?= e($user['role']) ?>" disabled
                       style="background:var(--light);cursor:not-allowed">
            </div>

            <div class="form-group">
                <label class="form-label">Akun Dibuat</label>
                <input type="text" class="form-control"
                       value="<?= date('d F Y, H:i', strtotime($user['created_at'])) ?>" disabled
                       style="background:var(--light);cursor:not-allowed">
            </div>

            <button type="submit" class="btn-add" style="align-self:flex-start;margin-top:4px">
                <i class="fas fa-save"></i> Simpan Profil
            </button>
        </form>
    </div>

    <!-- ── FORM PASSWORD ──────────────────────────────── -->
    <div class="table-card">
        <div class="table-card-header">
            <h3><i class="fas fa-lock" style="color:var(--p)"></i> Ganti Password</h3>
        </div>
        <form method="POST" style="padding:24px;display:flex;flex-direction:column;gap:18px;">
            <input type="hidden" name="action" value="password">

            <div class="form-group">
                <label class="form-label">Password Lama <span style="color:red">*</span></label>
                <div style="position:relative">
                    <input type="password" name="pass_lama" id="passLama" class="form-control"
                           placeholder="Masukkan password lama" required
                           style="padding-right: 44px;">
                    <button type="button" onclick="togglePass('passLama', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                               background:none;border:none;color:var(--gray);cursor:pointer;font-size:0.9rem">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:red">*</span></label>
                <div style="position:relative">
                    <input type="password" name="pass_baru" id="passBaru" class="form-control"
                           placeholder="Min. 6 karakter" required
                           style="padding-right: 44px;">
                    <button type="button" onclick="togglePass('passBaru', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                               background:none;border:none;color:var(--gray);cursor:pointer;font-size:0.9rem">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span style="color:red">*</span></label>
                <div style="position:relative">
                    <input type="password" name="pass_ulang" id="passUlang" class="form-control"
                           placeholder="Ulangi password baru" required
                           style="padding-right: 44px;">
                    <button type="button" onclick="togglePass('passUlang', this)"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                               background:none;border:none;color:var(--gray);cursor:pointer;font-size:0.9rem">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div id="strengthWrap" style="display:none">
                <div style="font-size:0.78rem;color:var(--gray);margin-bottom:5px">
                    Kekuatan password: <strong id="strengthText">-</strong>
                </div>
                <div style="height:5px;background:var(--border);border-radius:5px;overflow:hidden">
                    <div id="strengthBar" style="height:100%;width:0%;border-radius:5px;transition:all 0.3s"></div>
                </div>
            </div>

            <div style="background:#FEF3C7;border:1px solid #F59E0B;border-radius:8px;
                        padding:12px 16px;font-size:0.84rem;color:#92400E;display:flex;gap:10px;align-items:center">
                <i class="fas fa-exclamation-triangle" style="color:#F59E0B;flex-shrink:0"></i>
                Setelah ganti password, Anda akan otomatis <strong>logout</strong> dan harus login ulang.
            </div>

            <button type="submit" class="btn-add"
                    style="align-self:flex-start;margin-top:4px;background:linear-gradient(135deg,#059669,#10B981)"
                    onclick="return confirm('Yakin ingin mengganti password?')">
                <i class="fas fa-key"></i> Ganti Password
            </button>
        </form>
    </div>

</div>

<!-- Info Card -->
<div class="table-card" style="margin-top:28px">
    <div class="table-card-header">
        <h3><i class="fas fa-info-circle" style="color:var(--p)"></i> Informasi Akun</h3>
    </div>
    <div style="padding:24px;display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
        <div style="text-align:center;padding:20px;background:var(--light);border-radius:10px;">
            <div style="font-size:2rem;color:var(--p);margin-bottom:8px">
                <i class="fas fa-user-shield"></i>
            </div>
            <div style="font-size:0.78rem;color:var(--gray);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Username</div>
            <div style="font-size:1rem;font-weight:800;color:var(--dark)"><?= e($user['username']) ?></div>
        </div>
        <div style="text-align:center;padding:20px;background:var(--light);border-radius:10px;">
            <div style="font-size:2rem;color:#059669;margin-bottom:8px">
                <i class="fas fa-id-badge"></i>
            </div>
            <div style="font-size:0.78rem;color:var(--gray);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Role</div>
            <div style="font-size:1rem;font-weight:800;color:var(--dark)"><?= ucfirst(e($user['role'])) ?></div>
        </div>
        <div style="text-align:center;padding:20px;background:var(--light);border-radius:10px;">
            <div style="font-size:2rem;color:#F59E0B;margin-bottom:8px">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div style="font-size:0.78rem;color:var(--gray);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">Bergabung</div>
            <div style="font-size:1rem;font-weight:800;color:var(--dark)"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Password strength
const passBaru = document.getElementById('passBaru');
const wrap     = document.getElementById('strengthWrap');
const bar      = document.getElementById('strengthBar');
const txt      = document.getElementById('strengthText');

passBaru.addEventListener('input', function () {
    const v = this.value;
    wrap.style.display = v.length > 0 ? 'block' : 'none';
    let score = 0;
    if (v.length >= 6)  score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^a-zA-Z0-9]/.test(v)) score++;

    const levels = [
        { label: 'Sangat Lemah', color: '#EF4444', width: '20%' },
        { label: 'Lemah',        color: '#F97316', width: '40%' },
        { label: 'Sedang',       color: '#F59E0B', width: '60%' },
        { label: 'Kuat',         color: '#22C55E', width: '80%' },
        { label: 'Sangat Kuat',  color: '#059669', width: '100%' },
    ];
    const lvl = levels[Math.min(score, 4)];
    bar.style.width           = lvl.width;
    bar.style.backgroundColor = lvl.color;
    txt.textContent           = lvl.label;
    txt.style.color           = lvl.color;
});
</script>

<?php include 'footer.php'; ?>
