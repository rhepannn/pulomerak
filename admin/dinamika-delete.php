<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/dinamika.php');
$stmt=$conn->prepare("SELECT gambar FROM dinamika WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$row=$stmt->get_result()->fetch_assoc();
if($row&&$row['gambar']&&file_exists('../uploads/kegiatan/'.$row['gambar']))unlink('../uploads/kegiatan/'.$row['gambar']);
$del=$conn->prepare("DELETE FROM dinamika WHERE id=?");$del->bind_param('i',$id);$del->execute();
setFlash('success','Artikel berhasil dihapus!');redirect(SITE_URL.'/admin/dinamika.php');
