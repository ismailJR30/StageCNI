<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['idParticipant']) || empty($_GET['idParticipant'])) {
    header('Location: modification1.php');
    exit();
}

$cnx = getDbConnection();
$id = (int) $_GET['idParticipant'];

$stmt = mysqli_prepare($cnx, 'DELETE FROM participant WHERE id = ?');

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo 'Suppression effectuée avec succès.';
        echo ' <a href="modification1.php">Retour à la liste</a>';
        exit();
    }

    echo 'La suppression a échouée';
    mysqli_stmt_close($stmt);
} else {
    echo 'Erreur de préparation de la requête';
}
