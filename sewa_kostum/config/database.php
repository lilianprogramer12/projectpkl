<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sewa_kostum";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function formatRupiah($angka) {
    return "Rp. " . number_format($angka, 0, ',', '.');
}
?>