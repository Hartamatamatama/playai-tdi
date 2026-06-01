<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['character_name']) && isset($data['chat_history'])) {
    $user_id = $_SESSION['user_id'];
    $character_name = $conn->real_escape_string($data['character_name']);

    // Karena chat_history dari OpenRouter berbentuk Array Object, kita ubah jadi String JSON utuh agar bisa masuk MySQL
    $chat_history_string = is_array($data['chat_history']) ? json_encode($data['chat_history']) : $data['chat_history'];
    $chat_history_safe = $conn->real_escape_string($chat_history_string);

    // Kueri cerdas: Insert data baru, ATAU Timpa (Update) jika karakternya sudah ada
    $query = "INSERT INTO roleplay_saves (user_id, character_name, chat_history) 
              VALUES ('$user_id', '$character_name', '$chat_history_safe') 
              ON DUPLICATE KEY UPDATE chat_history = VALUES(chat_history)";

    if ($conn->query($query)) {
        echo json_encode(['status' => 'success', 'message' => 'Progres cerita otomatis disimpan!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan progres: ' . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nama karakter atau riwayat percakapan tidak lengkap.']);
}
