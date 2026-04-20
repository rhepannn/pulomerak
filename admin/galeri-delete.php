<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/galeri.php');
$stmt=$conn->prepare("SELECT gambar FROM galeri WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$row=$stmt->get_result()->fetch_assoc();
if($row&&$row['gambar']&&file_exists('../uploads/galeri/'.$row['gambar']))unlink('../uploads/galeri/'.$row['gambar']);
$del=$conn->prepare("DELETE FROM galeri WHERE id=?");$del->bind_param('i',$id);$del->execute();
setFlash('success','Foto berhasil dihapus!');redirect(SITE_URL.'/admin/galeri.php');
