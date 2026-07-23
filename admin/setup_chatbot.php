<?php
$baseUrl = getenv('OLLAMA_BASE_URL') ?: 'http://localhost:11434';
$model = getenv('OLLAMA_MODEL') ?: 'llama3';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baseUrl = trim($_POST['ollama_base_url'] ?? '');
    $model = trim($_POST['ollama_model'] ?? 'llama3');

    if ($baseUrl === '') {
        $baseUrl = 'http://localhost:11434';
    }

    if ($model === '') {
        $model = 'llama3';
    }

    $envFile = dirname(__DIR__) . '/.env';
    $envLines = file_exists($envFile)
        ? file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
        : [];

    $updatedLines = [];
    $baseFound = false;
    $modelFound = false;

    foreach ($envLines as $line) {
        if (strpos($line, 'OLLAMA_BASE_URL=') === 0) {
            $updatedLines[] = 'OLLAMA_BASE_URL=' . $baseUrl;
            $baseFound = true;
            continue;
        }

        if (strpos($line, 'OLLAMA_MODEL=') === 0) {
            $updatedLines[] = 'OLLAMA_MODEL=' . $model;
            $modelFound = true;
            continue;
        }

        $updatedLines[] = $line;
    }

    if (!$baseFound) {
        $updatedLines[] = 'OLLAMA_BASE_URL=' . $baseUrl;
    }

    if (!$modelFound) {
        $updatedLines[] = 'OLLAMA_MODEL=' . $model;
    }

    $result = file_put_contents($envFile, implode("\n", $updatedLines) . "\n");

    if ($result !== false) {
        $message = 'Configuration Ollama enregistrée avec succès.';
    } else {
        $message = 'Échec de l’écriture du fichier de configuration.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration chatbot</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Configuration du chatbot IA</h2>
        <p>Le chatbot utilise maintenant Ollama localement avec le modèle <strong>llama3</strong>. Aucune clé API n’est requise.</p>

        <?php if ($message !== ''): ?>
            <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
        <?php endif; ?>

        <form method="post">
            <label for="ollama_base_url">URL Ollama</label>
            <input type="text" id="ollama_base_url" name="ollama_base_url" value="<?php echo htmlspecialchars($baseUrl); ?>" placeholder="http://localhost:11434" required>

            <label for="ollama_model">Modèle</label>
            <input type="text" id="ollama_model" name="ollama_model" value="<?php echo htmlspecialchars($model); ?>" placeholder="llama3" required>

            <input type="submit" value="Enregistrer">
        </form>
    </div>
</body>
</html>
