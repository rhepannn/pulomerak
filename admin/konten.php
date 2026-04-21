<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$pageTitle = 'Editor Konten';

// Tab aktif
$tab = $_GET['tab'] ?? 'beranda_hero';

// Daftar grup/tab
$tabs = [
    'beranda_hero'    => ['label' => 'Hero Beranda',       'icon' => 'fas fa-home'],
    'beranda_stats'   => ['label' => 'Statistik Strip',    'icon' => 'fas fa-chart-bar'],
    'beranda_pkk'     => ['label' => 'Info PKK',           'icon' => 'fas fa-info-circle'],
    'beranda_counter' => ['label' => 'Counter Statistik',  'icon' => 'fas fa-sort-numeric-up'],
    'beranda_layanan' => ['label' => 'Layanan Unggulan',   'icon' => 'fas fa-cog'],
    'profil_hero'     => ['label' => 'Hero Profil',        'icon' => 'fas fa-id-card'],
    'profil_tentang'  => ['label' => 'Tentang Kecamatan',  'icon' => 'fas fa-landmark'],
    'profil_visimisi' => ['label' => 'Visi & Misi',        'icon' => 'fas fa-bullseye'],
    'profil_struktur' => ['label' => 'Struktur Organisasi','icon' => 'fas fa-sitemap'],
    'profil_batas'    => ['label' => 'Batas Wilayah',      'icon' => 'fas fa-map'],
    'footer'          => ['label' => 'Footer',             'icon' => 'fas fa-shoe-prints'],
];

// Upload directory for settings images
$uploadDir = __DIR__ . '/../uploads/settings/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group = $_POST['setting_group'] ?? '';
    $keys  = $_POST['keys'] ?? [];
    $vals  = $_POST['vals'] ?? [];
    $types = $_POST['field_types'] ?? [];
    
    foreach ($keys as $i => $key) {
        $fieldType = $types[$i] ?? 'text';
        
        if ($fieldType === 'image') {
            // Handle file upload
            $fileKey = 'file_' . $key;
            if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                $result = uploadFile($_FILES[$fileKey], $uploadDir);
                if (is_string($result) && !isset($result['error'])) {
                    // Delete old file if exists
                    $oldVal = getSetting($conn, $key);
                    if ($oldVal && file_exists($uploadDir . $oldVal)) {
                        @unlink($uploadDir . $oldVal);
                    }
                    setSetting($conn, $key, $result);
                }
            }
            // Check if user wants to remove image
            if (isset($_POST['remove_' . $key]) && $_POST['remove_' . $key] === '1') {
                $oldVal = getSetting($conn, $key);
                if ($oldVal && file_exists($uploadDir . $oldVal)) {
                    @unlink($uploadDir . $oldVal);
                }
                setSetting($conn, $key, '');
            }
        } else {
            // Normal text/textarea/number
            $val = $vals[$i] ?? '';
            setSetting($conn, $key, $val);
        }
    }
    
    setFlash('success', 'Konten berhasil diperbarui!');
    header('Location: konten.php?tab=' . urlencode($group));
    exit;
}

// Ambil settings untuk tab aktif
$settings = getSettingsByGroup($conn, $tab);

include 'header.php';
?>

<div class="page-title">
    <div>
        <h1><i class="fas fa-edit" style="color:var(--p)"></i> Editor Konten Website</h1>
        <p>Kelola semua teks, angka, gambar, dan deskripsi yang tampil di halaman Beranda & Profil</p>
    </div>
</div>

<!-- TAB NAVIGATION -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;">
    <?php foreach ($tabs as $key => $info): ?>
        <?php $isActive = ($tab === $key) ? 'background:var(--p);color:#fff;border-color:var(--p);' : ''; ?>
        <a href="konten.php?tab=<?= $key ?>" 
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;border:1.5px solid var(--border);text-decoration:none;color:var(--dark);transition:0.2s;<?= $isActive ?>">
            <i class="<?= $info['icon'] ?>" style="font-size:0.78rem;"></i>
            <?= $info['label'] ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- FORM EDITOR -->
<div class="table-card">
    <div class="table-card-header">
        <h3>
            <i class="<?= $tabs[$tab]['icon'] ?? 'fas fa-edit' ?>" style="color:var(--p)"></i>
            <?= $tabs[$tab]['label'] ?? 'Editor' ?>
        </h3>
        <span style="font-size:0.8rem;color:var(--gray);"><?= count($settings) ?> field</span>
    </div>

    <?php if (empty($settings)): ?>
        <div style="padding:60px;text-align:center;color:var(--gray);">
            <i class="fas fa-database" style="font-size:3rem;color:var(--border);display:block;margin-bottom:16px;"></i>
            <h3 style="margin-bottom:8px;">Belum Ada Data</h3>
            <p style="font-size:0.88rem;">Jalankan <code>setup_settings.php</code> terlebih dahulu untuk mengisi data default.</p>
        </div>
    <?php else: ?>
        <form method="POST" enctype="multipart/form-data" style="padding:24px;display:flex;flex-direction:column;gap:20px;">
            <input type="hidden" name="setting_group" value="<?= e($tab) ?>">
            
            <?php foreach ($settings as $idx => $s): ?>
                <div class="form-group" style="<?= $s['field_type'] === 'image' ? 'background:var(--light);padding:20px;border-radius:12px;border:1px dashed var(--border);' : '' ?>">
                    <label class="form-label" style="display:flex;align-items:center;gap:8px;">
                        <?php if ($s['field_type'] === 'image'): ?>
                            <i class="fas fa-image" style="color:var(--p);"></i>
                        <?php endif; ?>
                        <?= e($s['label'] ?? $s['setting_key']) ?>
                        <span style="font-size:0.7rem;color:var(--gray);background:#fff;padding:2px 8px;border-radius:4px;">
                            <?= e($s['setting_key']) ?>
                        </span>
                    </label>
                    <input type="hidden" name="keys[]" value="<?= e($s['setting_key']) ?>">
                    <input type="hidden" name="field_types[]" value="<?= e($s['field_type']) ?>">
                    
                    <?php if ($s['field_type'] === 'image'): ?>
                        <!-- IMAGE UPLOAD FIELD -->
                        <?php 
                        $currentImg = $s['setting_value'] ?? '';
                        $imgUrl = '';
                        if ($currentImg) {
                            $imgPath = __DIR__ . '/../uploads/settings/' . $currentImg;
                            if (file_exists($imgPath)) {
                                $imgUrl = SITE_URL . '/uploads/settings/' . $currentImg;
                            }
                        }
                        ?>
                        <input type="hidden" name="vals[]" value="">
                        
                        <?php if ($imgUrl): ?>
                            <div style="margin-bottom:12px;position:relative;display:inline-block;">
                                <img src="<?= e($imgUrl) ?>" alt="Preview" 
                                     style="max-width:300px;max-height:200px;border-radius:10px;border:2px solid var(--border);object-fit:cover;display:block;">
                                <label style="position:absolute;top:8px;right:8px;display:flex;align-items:center;gap:6px;background:rgba(220,38,38,0.9);color:#fff;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;cursor:pointer;">
                                    <input type="checkbox" name="remove_<?= e($s['setting_key']) ?>" value="1" style="display:none;" 
                                           onchange="this.closest('div').style.opacity = this.checked ? '0.3' : '1'">
                                    <i class="fas fa-trash"></i> Hapus
                                </label>
                                <div style="font-size:0.75rem;color:var(--gray);margin-top:6px;">
                                    <i class="fas fa-check-circle" style="color:#059669;"></i> <?= e($currentImg) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div style="display:flex;align-items:center;gap:12px;">
                            <label style="display:flex;align-items:center;gap:8px;padding:10px 20px;background:#fff;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;transition:0.2s;font-size:0.88rem;font-weight:600;color:var(--dark);"
                                   onmouseover="this.style.borderColor='var(--p)';this.style.background='var(--light)'" 
                                   onmouseout="this.style.borderColor='var(--border)';this.style.background='#fff'">
                                <i class="fas fa-upload" style="color:var(--p);"></i>
                                <?= $imgUrl ? 'Ganti Gambar' : 'Upload Gambar' ?>
                                <input type="file" name="file_<?= e($s['setting_key']) ?>" accept="image/*" style="display:none;"
                                       onchange="previewImg(this, '<?= e($s['setting_key']) ?>')">
                            </label>
                            <span id="fname_<?= e($s['setting_key']) ?>" style="font-size:0.8rem;color:var(--gray);"></span>
                        </div>
                        
                        <!-- Preview area for new upload -->
                        <div id="preview_<?= e($s['setting_key']) ?>" style="margin-top:10px;display:none;">
                            <img id="previmg_<?= e($s['setting_key']) ?>" src="" alt="Preview baru" 
                                 style="max-width:300px;max-height:200px;border-radius:10px;border:2px solid var(--p);object-fit:cover;">
                            <div style="font-size:0.78rem;color:var(--p);margin-top:4px;font-weight:600;">
                                <i class="fas fa-eye"></i> Preview gambar baru
                            </div>
                        </div>
                        
                        <small style="font-size:0.75rem;color:var(--gray);margin-top:6px;display:block;">
                            <i class="fas fa-info-circle"></i> Format: JPG, PNG, GIF, WEBP. Maks 5MB.
                        </small>

                    <?php elseif ($s['field_type'] === 'textarea'): ?>
                        <textarea name="vals[]" class="form-control" rows="4" 
                                  style="resize:vertical;min-height:80px;"
                                  placeholder="<?= e($s['label'] ?? '') ?>"><?= e($s['setting_value'] ?? '') ?></textarea>
                        <?php if ($s['setting_key'] === 'profil_misi'): ?>
                            <small style="color:var(--gray);font-size:0.78rem;margin-top:4px;display:block;">
                                <i class="fas fa-info-circle"></i> Tulis satu misi per baris (tekan Enter untuk baris baru)
                            </small>
                        <?php endif; ?>
                        <?php if (str_contains($s['setting_key'] ?? '', 'hero_title')): ?>
                            <small style="color:var(--gray);font-size:0.78rem;margin-top:4px;display:block;">
                                <i class="fas fa-info-circle"></i> Gunakan <code>&lt;br&gt;</code> untuk baris baru dan <code>&lt;span&gt;teks&lt;/span&gt;</code> untuk teks berwarna
                            </small>
                        <?php endif; ?>
                    <?php elseif ($s['field_type'] === 'number'): ?>
                        <input type="number" name="vals[]" class="form-control"
                               value="<?= e($s['setting_value'] ?? '') ?>"
                               placeholder="<?= e($s['label'] ?? '') ?>">
                    <?php else: ?>
                        <input type="text" name="vals[]" class="form-control"
                               value="<?= e($s['setting_value'] ?? '') ?>"
                               placeholder="<?= e($s['label'] ?? '') ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div style="display:flex;gap:12px;align-items:center;padding-top:8px;border-top:1px solid var(--border);">
                <button type="submit" class="btn-add">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="konten.php?tab=<?= e($tab) ?>" style="font-size:0.88rem;color:var(--gray);text-decoration:none;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>

<!-- PREVIEW INFO -->
<div class="table-card" style="margin-top:20px;">
    <div style="padding:20px;display:flex;align-items:center;gap:16px;background:var(--light);border-radius:10px;">
        <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--p),#2563EB);color:#fff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
            <i class="fas fa-eye"></i>
        </div>
        <div>
            <h4 style="font-size:0.92rem;color:var(--dark);margin-bottom:4px;">Lihat Hasil</h4>
            <p style="font-size:0.82rem;color:var(--gray);margin:0;">
                Setelah menyimpan, buka 
                <?php if (str_starts_with($tab, 'beranda_')): ?>
                    <a href="<?= SITE_URL ?>/" target="_blank" style="color:var(--p);font-weight:700;">Halaman Beranda <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i></a>
                <?php elseif (str_starts_with($tab, 'profil_')): ?>
                    <a href="<?= SITE_URL ?>/profil.php" target="_blank" style="color:var(--p);font-weight:700;">Halaman Profil <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i></a>
                <?php else: ?>
                    <a href="<?= SITE_URL ?>/" target="_blank" style="color:var(--p);font-weight:700;">Website <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i></a>
                <?php endif; ?>
                untuk melihat perubahannya.
            </p>
        </div>
    </div>
</div>

<script>
function previewImg(input, key) {
    const preview = document.getElementById('preview_' + key);
    const img = document.getElementById('previmg_' + key);
    const fname = document.getElementById('fname_' + key);
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate size
        if (file.size > 5 * 1024 * 1024) {
            alert('File terlalu besar! Maksimal 5MB.');
            input.value = '';
            return;
        }
        
        // Validate type
        if (!file.type.match('image.*')) {
            alert('Hanya file gambar yang diperbolehkan!');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        fname.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
    }
}
</script>

<?php include 'footer.php'; ?>
