<?php
// Izinkan akses dari mana saja untuk pengujian lokal
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$input = json_decode(file_get_contents('php://input'), true);
$prompt = isset($input['inputs']) ? $input['inputs'] : '';

if (empty($prompt)) {
    http_response_code(400);
    echo "Prompt ide stiker tidak boleh kosong.";
    exit;
}

// Interseptor AI: Memastikan elemen yang tidak diinginkan (seperti plat nomor pada motor) otomatis dihapus dari kanvas
if (stripos($prompt, 'satria fu') !== false || stripos($prompt, 'motor') !== false) {
    $prompt .= ", without any license plates, clean front and rear, no text on vehicle";
}

// Trik Anti-Cooldown: Kita acak seed dari sisi server agar AI selalu merender ulang gambar baru
$seed = rand(1, 1000000);

// Encode teks agar aman dikirim melalui URL
$safe_prompt = urlencode($prompt);

// MENGGUNAKAN JALUR POLLINATIONS AI (Model FLUX, Anti-Blokir, Tanpa API Key)
$api_url = "https://image.pollinations.ai/prompt/{$safe_prompt}?width=512&height=512&seed={$seed}&nologo=true&model=flux";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// Menyamar sebagai browser sungguhan untuk menembus firewall
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Evaluasi Balasan Server
if ($http_code == 200) {
    // Sukses: Langsung kirim data gambar ke React Frontend
    header('Content-Type: image/jpeg');
    echo $response;
} else {
    // Gagal: Kirim pesan error berbentuk teks untuk dibaca oleh Frontend
    http_response_code(400);
    echo "Server AI Pollinations sedang sibuk merender. Silakan klik tombol Generate sekali lagi.";
}
