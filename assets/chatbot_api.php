<?php
header('Content-Type: application/json');

function sendJson($payload)
{
    echo json_encode($payload);
    exit;
}

function getOllamaBaseUrl(): string
{
    $candidates = [
        getenv('OLLAMA_BASE_URL'),
        $_ENV['OLLAMA_BASE_URL'] ?? null,
        $_SERVER['OLLAMA_BASE_URL'] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if (is_string($candidate) && trim($candidate) !== '') {
            return rtrim(trim($candidate), '/');
        }
    }

    return 'http://localhost:11434';
}

function getOllamaModel(): string
{
    $candidates = [
        getenv('OLLAMA_MODEL'),
        $_ENV['OLLAMA_MODEL'] ?? null,
        $_SERVER['OLLAMA_MODEL'] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if (is_string($candidate) && trim($candidate) !== '') {
            return trim($candidate);
        }
    }

    return 'llama3';
}

$input = json_decode(file_get_contents('php://input'), true);
$message = trim($input['message'] ?? '');

if ($message === '') {
    sendJson(['reply' => 'Veuillez écrire un message avant d’envoyer.']);
}

$baseUrl = getOllamaBaseUrl();
$model = getOllamaModel();

$payload = [
    'model' => $model,
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
    'stream' => false,
    'options' => [
        'temperature' => 0.7,
        'num_predict' => 800,
    ]
];

$ch = curl_init(rtrim($baseUrl, '/') . '/api/chat');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
    sendJson([
        'reply' => 'Le service Ollama est indisponible pour le moment. Vérifiez que le serveur est démarré sur http://localhost:11434.'
    ]);
}

$decoded = json_decode($response, true);
$reply = $decoded['message']['content'] ?? 'Je n’ai pas pu générer une réponse pour le moment.';

sendJson(['reply' => trim($reply)]);
