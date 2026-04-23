<?php
require_once 'include/config.php';

$data = [
    'LEBAKGEDE' => [
        'jumlah_link' => 25, 'jumlah_rw' => 9, 'jumlah_rt' => 46, 'dasa_wisma' => 16,
        'ibu_hamil' => 80, 'ibu_melahirkan' => 217, 'ibu_nifas' => 37, 'ibu_meninggal' => 0,
        'bayi_lahir_l' => 109, 'bayi_lahir_p' => 104, 'akte_ada' => 187, 'akte_tidak' => 0,
        'bayi_meninggal_l' => 0, 'bayi_meninggal_p' => 0, 'balita_meninggal_l' => 0, 'balita_meninggal_p' => 0
    ],
    'TAMANSARI' => [
        'jumlah_link' => 18, 'jumlah_rw' => 6, 'jumlah_rt' => 36, 'dasa_wisma' => 0,
        'ibu_hamil' => 712, 'ibu_melahirkan' => 230, 'ibu_nifas' => 230, 'ibu_meninggal' => 0,
        'bayi_lahir_l' => 28, 'bayi_lahir_p' => 30, 'akte_ada' => 59, 'akte_tidak' => 5,
        'bayi_meninggal_l' => 0, 'bayi_meninggal_p' => 2, 'balita_meninggal_l' => 0, 'balita_meninggal_p' => 0
    ],
    'MEKARSARI' => [
        'jumlah_link' => 25, 'jumlah_rw' => 7, 'jumlah_rt' => 32, 'dasa_wisma' => 6,
        'ibu_hamil' => 579, 'ibu_melahirkan' => 171, 'ibu_nifas' => 171, 'ibu_meninggal' => 1,
        'bayi_lahir_l' => 41, 'bayi_lahir_p' => 33, 'akte_ada' => 68, 'akte_tidak' => 6,
        'bayi_meninggal_l' => 1, 'bayi_meninggal_p' => 0, 'balita_meninggal_l' => 0, 'balita_meninggal_p' => 0
    ],
    'SURALAYA' => [
        'jumlah_link' => 18, 'jumlah_rw' => 5, 'jumlah_rt' => 18, 'dasa_wisma' => 5,
        'ibu_hamil' => 131, 'ibu_melahirkan' => 113, 'ibu_nifas' => 113, 'ibu_meninggal' => 0,
        'bayi_lahir_l' => 39, 'bayi_lahir_p' => 52, 'akte_ada' => 109, 'akte_tidak' => 2,
        'bayi_meninggal_l' => 0, 'bayi_meninggal_p' => 3, 'balita_meninggal_l' => 0, 'balita_meninggal_p' => 1
    ]
];

foreach ($data as $nama => $vals) {
    $sql = "UPDATE kelurahan SET 
            jumlah_link = ?, jumlah_rw = ?, jumlah_rt = ?, dasa_wisma = ?, 
            ibu_hamil = ?, ibu_melahirkan = ?, ibu_nifas = ?, ibu_meninggal = ?, 
            bayi_lahir_l = ?, bayi_lahir_p = ?, akte_ada = ?, akte_tidak = ?, 
            bayi_meninggal_l = ?, bayi_meninggal_p = ?, balita_meninggal_l = ?, balita_meninggal_p = ? 
            WHERE UPPER(nama) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiiiiiiiiiiis", 
        $vals['jumlah_link'], $vals['jumlah_rw'], $vals['jumlah_rt'], $vals['dasa_wisma'],
        $vals['ibu_hamil'], $vals['ibu_melahirkan'], $vals['ibu_nifas'], $vals['ibu_meninggal'],
        $vals['bayi_lahir_l'], $vals['bayi_lahir_p'], $vals['akte_ada'], $vals['akte_tidak'],
        $vals['bayi_meninggal_l'], $vals['bayi_meninggal_p'], $vals['balita_meninggal_l'], $vals['balita_meninggal_p'],
        $nama
    );
    $stmt->execute();
}

// Update Secretary in site_settings
$sekretaris = "Ny. Iffah Lathifah, S.Psi";
$stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'org_sekcam_nama'");
$stmt->bind_param("s", $sekretaris);
$stmt->execute();

// Also update the role for secretary to Secretary TP PKK instead of Secretary Camat
$role = "Sekretaris TP PKK Kecamatan";
$stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'org_sekcam_jabatan'");
$stmt->bind_param("s", $role);
$stmt->execute();

echo "Data berhasil diupdate berdasarkan rekapitulasi 2025.";
unlink(__FILE__); // Hapus diri sendiri setelah jalan
?>
