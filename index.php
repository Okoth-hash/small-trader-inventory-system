<?php
session_start();
define('BASE_URL', '/small-trader-inventory-system/');
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard.php');
} else {
    header('Location: ' . BASE_URL . 'auth/login.php');
}
exit();
?>
