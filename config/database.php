<?php
$host   = getenv('MYSQLHOST')     ?: 'sql213.infinityfree.com';
$user   = getenv('MYSQLUSER')     ?: 'if0_41321571';
$pass   = getenv('MYSQLPASSWORD') ?: 'Okothrobin1234';
$dbname = getenv('MYSQLDATABASE') ?: 'if0_41321571_trader';
$port   = (int)(getenv('MYSQLPORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
