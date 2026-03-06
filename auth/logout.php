<?php
session_start();
session_unset();
session_destroy();
header('Location: /small-trader-inventory-system/auth/login.php');
exit();
?>
