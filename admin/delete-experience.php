<?php
require_once 'includes/admin_auth.php';
require_once '../includes/connection.php';
$id=(int)($_GET['id']??0);
if (!$id) { header('Location: experiences.php'); exit; }
$stmt=mysqli_prepare($conn,"DELETE FROM experiences WHERE experience_id=?");
mysqli_stmt_bind_param($stmt,'i',$id); mysqli_stmt_execute($stmt);
header('Location: experiences.php?deleted=1'); exit;
