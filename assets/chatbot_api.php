<?php
header('Content-Type: application/json');

function sendJson($payload) {
    echo json_encode($payload);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = trim($input['message'] ?? '');

if ($message === '') {
    sendJson(['reply' => 'Veuillez écrire un message avant d’envoyer.']);
}

$envFile = dirname(__DIR__) . '/.env';
$apiKey = getenv('OPENAI_API_KEY');

if (empty($apiKey) && file_exists($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos($line, 'OPENAI_API_KEY=') === 0) {
            $apiKey = trim(substr($line, strlen('OPENAI_API_KEY=')));
            break;
        }
    }
}

if (empty($apiKey) && isset($_SERVER['HTTP_X_OPENAI_API_KEY']) && $_SERVER['HTTP_X_OPENAI_API_KEY'] !== '') {
    $apiKey = trim($_SERVER['HTTP_X_OPENAI_API_KEY']);
}

if (empty($apiKey)) {
    sendJson([
        'reply' => 'Le chatbot IA n’est pas encore configuré. Ouvrez la page de configuration dans l’administration et entrez votre clé API OpenAI.'
    ]);
}

$payload = [
    'model' => 'gpt-4o-mini',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Tu es un assistant IA utile pour un site web de formation. Réponds en français, de manière courte et claire, et aide l’utilisateur à trouver des informations sur les inscriptions, les cycles, les participants et l’administration.'
        ],
        [
            'role' => 'user',
            'content' => $message
        ]
    ],
    'temperature' => 0.7
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
    sendJson([
        'reply' => 'Le service IA est indisponible pour le moment. Veuillez réessayer plus tard.'
    ]);
}

$decoded = json_decode($response, true);
$reply = $decoded['choices'][0]['message']['content'] ?? 'Je n’ai pas pu générer une réponse pour le moment.';

sendJson(['reply' => trim($reply)]);
