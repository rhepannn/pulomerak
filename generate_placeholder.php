<?php
// ============================================================
// GENERATE PLACEHOLDER IMAGE - Portal Kecamatan Pulomerak
// Jalankan sekali: php generate_placeholder.php
// ============================================================

$w = 400; $h = 260;
$img = imagecreatetruecolor($w, $h);

$bg1 = imagecolorallocate($img, 26,  79, 160);   // biru primer
$bg2 = imagecolorallocate($img, 15,  50, 114);   // biru gelap
$fg  = imagecolorallocate($img, 255,255, 255);   // putih
$acc = imagecolorallocate($img,  39,174,  96);   // hijau

// Gradient manual
for ($y = 0; $y < $h; $y++) {
    $ratio = $y / $h;
    $r = (int)(26  + $ratio * (15  - 26));
    $g = (int)(79  + $ratio * (50  - 79));
    $b = (int)(160 + $ratio * (114 - 160));
    $c = imagecolorallocate($img, $r, $g, $b);
    imageline($img, 0, $y, $w, $y, $c);
}

// Lingkaran dekoratif
imageellipse($img, 350, 30,  200, 200, imagecolorallocatealpha($img, 255,255,255, 110));
imageellipse($img,  50, 220, 150, 150, imagecolorallocatealpha($img, 39,174,96,  110));

// Teks
$font = 5;
$text1 = 'Kecamatan Pulomerak';
$text2 = 'Kota Cilegon';
$tw1 = strlen($text1) * imagefontwidth($font);
$tw2 = strlen($text2) * imagefontwidth($font);
imagestring($img, $font, ($w - $tw1) / 2, $h/2 - 20, $text1, $fg);
imagestring($img, 3,     ($w - $tw2) / 2, $h/2 + 10, $text2, $fg);

// Simpan
$dest = __DIR__ . '/assets/img/placeholder.jpg';
imagejpeg($img, $dest, 90);
imagedestroy($img);

echo "Placeholder berhasil dibuat: $dest\n";
