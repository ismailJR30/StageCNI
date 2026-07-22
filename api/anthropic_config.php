<?php
function getApiKey(): string
{
    $candidates = [];

    $candidates[] = getenv('GOOGLE_API_KEY');
    $candidates[] = getenv('GEMINI_API_KEY');
    $candidates[] = getenv('ANTHROPIC_API_KEY');
    $candidates[] = getenv('HTTP_GOOGLE_API_KEY');
    $candidates[] = getenv('HTTP_GEMINI_API_KEY');
    $candidates[] = $_ENV['GOOGLE_API_KEY'] ?? null;
    $candidates[] = $_ENV['GEMINI_API_KEY'] ?? null;
    $candidates[] = $_SERVER['GOOGLE_API_KEY'] ?? null;
    $candidates[] = $_SERVER['GEMINI_API_KEY'] ?? null;

    foreach ([__DIR__ . '/google.key', __DIR__ . '/anthropic.key'] as $localFile) {
        if (is_file($localFile)) {
            $candidates[] = trim((string) file_get_contents($localFile));
        }
    }

    foreach ($candidates as $candidate) {
        if (is_string($candidate) && trim($candidate) !== '') {
            return trim($candidate);
        }
    }

    return '';
}
