<?php
$apiKey = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = trim($_POST['api_key'] ?? '');

    if ($apiKey !== '') {
        $envFile = dirname(__DIR__) . '/.env';
        $content = "OPENAI_API_KEY={$apiKey}\n";
        $result = file_put_contents($envFile, $content);

        if ($result !== false) {
            $message = 'Clé API enregistrée avec succès.';
        } else {
            $message = 'Échec de l’écriture du fichier de configuration.';
        }
    } else {
        $message = 'Veuillez saisir une clé API.';
    }
}

$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $envLines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos($line, 'OPENAI_API_KEY=') === 0) {
            $apiKey = trim(substr($line, strlen('OPENAI_API_KEY=')));
            break;
        }
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
        <p>Entrez votre clé API OpenAI pour activer les réponses intelligentes.</p>

        <?php if ($message !== ''): ?>
            <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
        <?php endif; ?>

        <form method="post">
            <label for="api_key">Clé API OpenAI</label>
            <input type="text" id="api_key" name="api_key" value="<?php echo htmlspecialchars($apiKey); ?>" placeholder="sk-..." required>
            <input type="submit" value="Enregistrer">
        </form>
    </div>
</body>
</html>
