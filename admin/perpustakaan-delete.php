<?php
require_once '../include/config.php';
require_once '../include/functions.php';
requireAdmin();
$id=(int)($_GET['id']??0);if(!$id)redirect(SITE_URL.'/admin/perpustakaan.php');
$stmt=$conn->prepare("SELECT file FROM perpustakaan WHERE id=?");$stmt->bind_param('i',$id);$stmt->execute();
$row=$stmt->get_result()->fetch_assoc();
if($row&&$row['file']&&file_exists('../uploads/perpustakaan/'.$row['file']))unlink('../uploads/perpustakaan/'.$row['file']);
$del=$conn->prepare("DELETE FROM perpustakaan WHERE id=?");$del->bind_param('i',$id);$del->execute();
setFlash('success','Dokumen berhasil dihapus!');redirect(SITE_URL.'/admin/perpustakaan.php');
