<?php
$host   = 'sql213.infinityfree.com';
$user   = 'if0_41321571';
$pass   = 'Okothrobin1234';
$dbname = 'if0_41321571_trader';
$port   = 3306;
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$conn->set_charset("utf8");
?>
