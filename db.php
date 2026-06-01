<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default password MySQL di XAMPP memang kosong
$dbname = 'playai_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}
