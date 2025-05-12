<?php
$host = "localhost";
$user = "root";
$pass = ""; // GANTI ke "root" jika perlu
$db = "Todos";

$Conn = new mysqli($host, $user, $pass, $db);

if ($Conn->connect_error) {
    die("Koneksi gagal: " . $Conn->connect_error);
}

session_start();
?>
