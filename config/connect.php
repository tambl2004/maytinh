<?php
// kết nối database bằng mysqli
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'qlsv';
$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>