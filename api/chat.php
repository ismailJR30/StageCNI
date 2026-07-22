<?php
require_once __DIR__ . '/tools.php';
require_once __DIR__ . '/anthropic_config.php';

header('Content-Type: application/json; charset=utf-8');

function sendGoogleRequest(array $payload): array
{
    $apiKey = getApiKey();

    if ($apiKey === '') {
        throw new RuntimeException('La clé API Google AI Studio n’est pas configurée. Définissez GOOGLE_API_KEY ou GEMINI_API_KEY dans votre environnement serveur.');
    }

    if (stripos($apiKey, 'AIza') !== 0) {
        throw new RuntimeException('La clé fournie ne ressemble pas à une clé Google AI Studio valide. Les clés Google commencent généralement par "AIza".');
    }

    $models = ['gemini-1.5-flash-latest', 'gemini-1.5-flash', 'gemini-2.0-flash-exp', 'gemini-2.0-flash'];
    $lastError = null;

    foreach ($models as $model) {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . urlencode($apiKey);
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            $lastError = 'Erreur cURL : ' . $error;
            continue;
        }

        $decoded = json_decode($response, true);

        if (is_array($decoded) && (!isset($decoded['error']) || $httpCode < 400)) {
            return $decoded;
        }

        $lastError = is_array($decoded) && isset($decoded['error']['message'])
            ? $decoded['error']['message']
            : ($error ?: 'HTTP ' . $httpCode);
    }

    throw new RuntimeException('Erreur API Google AI Studio : ' . $lastError);
}

function buildSystemPrompt(): string
{
    return 'Tu es un assistant de support pour un centre de formation. Réponds en français, de façon polie et concise. Si la question concerne les formations ou les formateurs, réponds avec les informations disponibles.';
}

try {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $userMessage = trim((string) ($input['message'] ?? ''));

    if ($userMessage === '') {
        throw new InvalidArgumentException('Le message utilisateur est vide.');
    }

    $payload = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [[
                    'text' => $userMessage,
                ]],
            ],
        ],
        'generationConfig' => [
            'temperature' => 0.2,
            'maxOutputTokens' => 800,
        ],
        'systemInstruction' => [
            'parts' => [[
                'text' => buildSystemPrompt(),
            ]],
        ],
    ];

    $response = sendGoogleRequest($payload);
    $candidates = $response['candidates'] ?? [];
    $candidate = $candidates[0] ?? [];
    $parts = $candidate['content']['parts'] ?? [];
    $reply = '';

    foreach ($parts as $part) {
        if (isset($part['text'])) {
            $reply .= $part['text'];
        }
    }

    echo json_encode([
        'reply' => $reply !== '' ? $reply : 'Désolé, je n’ai pas pu répondre à votre demande.',
        'success' => true,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'reply' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
