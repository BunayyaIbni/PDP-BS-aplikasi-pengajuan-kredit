<?php
// config/database.php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kredit_kendaraan';

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>