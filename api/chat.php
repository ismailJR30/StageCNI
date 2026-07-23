<?php
require_once __DIR__ . '/tools.php';
require_once __DIR__ . '/anthropic_config.php';

header('Content-Type: application/json; charset=utf-8');

function sendOllamaRequest(array $messages): array
{
    $baseUrl = getOllamaBaseUrl();
    $model = getOllamaModel();
    $url = rtrim($baseUrl, '/') . '/api/chat';

    $payload = [
        'model' => $model,
        'messages' => $messages,
        'stream' => false,
        'options' => [
            'temperature' => 0.2,
            'num_predict' => 800,
        ],
    ];

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
        throw new RuntimeException('Erreur cURL Ollama : ' . $error);
    }

    $decoded = json_decode($response, true);

    if (!is_array($decoded) || $httpCode >= 400 || !isset($decoded['message']['content'])) {
        $message = is_array($decoded) && isset($decoded['error'])
            ? $decoded['error']
            : ($error ?: 'HTTP ' . $httpCode);

        throw new RuntimeException('Erreur Ollama : ' . $message);
    }

    return $decoded;
}

function buildSystemPrompt(): string
{
    return 'Tu es un assistant de support pour un centre de formation. Réponds en français, de façon polie et concise. Si la question concerne les formations ou les formateurs, réponds avec les informations disponibles.';
}

try {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $userMessage = trim((string) ($input['message'] ?? ''));
    $history = is_array($input['history'] ?? null) ? $input['history'] : [];

    if ($userMessage === '') {
        throw new InvalidArgumentException('Le message utilisateur est vide.');
    }

    $messages = [
        [
            'role' => 'system',
            'content' => buildSystemPrompt(),
        ],
    ];

    foreach ($history as $entry) {
        $role = isset($entry['role']) && in_array($entry['role'], ['user', 'assistant', 'system'], true)
            ? $entry['role']
            : 'user';

        $content = isset($entry['content']) ? (string) $entry['content'] : '';

        if ($content !== '') {
            $messages[] = [
                'role' => $role,
                'content' => $content,
            ];
        }
    }

    $messages[] = [
        'role' => 'user',
        'content' => $userMessage,
    ];

    $response = sendOllamaRequest($messages);
    $reply = $response['message']['content'] ?? '';

    echo json_encode([
        'reply' => trim($reply) !== '' ? trim($reply) : 'Désolé, je n’ai pas pu répondre à votre demande.',
        'success' => true,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'reply' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
