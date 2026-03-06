<?php
session_start();

\['user_id'] = 1;
\['email'] = 'admin@system.local';
\['logged_in'] = true;

header("Location: ../dashboard/index.php");
exit();
?>
