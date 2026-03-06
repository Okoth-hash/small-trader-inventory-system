<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "kelly");
define("DB_NAME", "small_trader_db");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
