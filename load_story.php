<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$character_name = isset($_GET['character']) ? $conn->real_escape_string($_GET['character']) : '';

if (empty($character_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Nama karakter tidak dikirim.']);
    exit();
}

$query = "SELECT chat_history FROM roleplay_saves WHERE user_id = '$user_id' AND character_name = '$character_name' LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Kirim data riwayat chat ke Javascript
    echo json_encode(['status' => 'success', 'data' => $row['chat_history']]);
} else {
    // Jika belum pernah main karakter ini, kirim status empty (kosong)
    echo json_encode(['status' => 'empty', 'message' => 'Belum ada save data untuk karakter ini.']);
}
