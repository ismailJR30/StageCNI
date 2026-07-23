<?php
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
