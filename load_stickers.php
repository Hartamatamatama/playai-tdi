<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua stiker milik user ini, urutkan dari yang terbaru (DESC)
$query = "SELECT prompt, image_url, created_at FROM stickers WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($query);

$stickers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stickers[] = $row;
    }
}

// Kirim balik ke Javascript
echo json_encode(['status' => 'success', 'data' => $stickers]);
