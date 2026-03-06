<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
require_once '../../config/database.php';
$uid=$_SESSION['user_id']; $id=intval($_GET['id']??0);
$stmt=$conn->prepare("DELETE FROM products WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$id,$uid);
$_SESSION[$stmt->execute()&&$stmt->affected_rows>0?'success':'error']=$stmt->execute()&&$stmt->affected_rows>0?'Product deleted.':'Could not delete.';
header('Location: products.php'); exit();
?>
