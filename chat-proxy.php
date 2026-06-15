<?php
// chat-proxy.php
// Server-side proxy for OpenRouter AI — keeps the API key hidden from clients

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$OR_KEY = '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';
$OR_MODEL = 'mistralai/mistral-7b-instruct:free';
$OR_URL = 'https://openrouter.ai/api/v1/chat/completions';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['messages'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing messages']);
    exit;
}

$payload = [
    'model' => $OR_MODEL,
    'messages' => $input['messages'],
    'max_tokens' => $input['max_tokens'] ?? 600,
    'temperature' => $input['temperature'] ?? 0.7,
];

$ch = curl_init($OR_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $OR_KEY,
        'HTTP-Referer: https://aashiyanawelfareworld.org',
        'X-Title: Aashiyana Chatbot',
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    echo json_encode(['error' => $error]);
    exit;
}

http_response_code($httpCode);
echo $response;
