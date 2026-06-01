<?php
session_start();
require_once 'db.php';

// Pastikan balasan ke frontend selalu dalam bentuk JSON
header('Content-Type: application/json');

// Keamanan: Tolak jika yang menembak file ini belum login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
    exit();
}

// Tangkap payload JSON dari frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['prompt']) && isset($data['image_url'])) {
    $user_id = $_SESSION['user_id'];
    // Amankan dari SQL Injection
    $prompt = $conn->real_escape_string($data['prompt']);
    $image_url = $conn->real_escape_string($data['image_url']);

    $query = "INSERT INTO stickers (user_id, prompt, image_url) VALUES ('$user_id', '$prompt', '$image_url')";

    if ($conn->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Stiker berhasil disimpan ke galeri!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database: ' . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data prompt atau gambar tidak lengkap.']);
}
