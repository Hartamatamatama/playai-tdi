<?php
header('Content-Type: application/json');

// Allow CORS for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Panggil file konfigurasi rahasia
require_once 'config.php';

// Ambil kuncinya (Disamakan menggunakan huruf K besar)
$apiKey = OPENROUTER_API_KEY;

// Get the request body from client
$input = file_get_contents('php://input');

// Validasi apakah input benar-benar JSON yang valid
if (empty($input) || !json_decode($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit();
}

// OpenRouter API endpoint
$url = 'https://openrouter.ai/api/v1/chat/completions';

// Initialize cURL
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $input, // Langsung kirim raw JSON
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey, // Variabel sekarang sudah cocok
        'HTTP-Referer: http://playai.my.id', // Gunakan domain aslimu
        'X-Title: Anime RPG Adventure'
    ],
    CURLOPT_TIMEOUT => 240, // 4 minutes timeout
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($error) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . $error]);
    exit();
}

http_response_code($httpCode);
echo $response;