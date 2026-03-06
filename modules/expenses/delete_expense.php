<?php
session_start();
define('BASE_URL', '/');
require_once '../../config/database.php';
$uid=$_SESSION['user_id']; $id=intval($_GET['id']??0);
$stmt=$conn->prepare("DELETE FROM expenses WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$id,$uid); $stmt->execute();
$_SESSION['success']='Expense deleted.';
header('Location: expenses_list.php'); exit();
?>
