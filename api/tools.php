<?php
require_once __DIR__ . '/db.php';

function lister_formations(?string $theme = null): array
{
    $pdo = getDbConnection();
    $sql = 'SELECT id, num_act, theme, date_deb, date_fin, for1, for2, for3, num_salle FROM cycle';
    $params = [];

    if (!empty($theme)) {
        $sql .= ' WHERE theme LIKE :theme OR num_act LIKE :theme OR for1 LIKE :theme OR for2 LIKE :theme OR for3 LIKE :theme';
        $params[':theme'] = '%' . $theme . '%';
    }

    $sql .= ' ORDER BY date_deb DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    return [
        'count' => count($rows),
        'formations' => array_map(function ($row) {
            return [
                'id' => (int) $row['id'],
                'numero_acte' => $row['num_act'],
                'theme' => $row['theme'],
                'date_debut' => $row['date_deb'],
                'date_fin' => $row['date_fin'],
                'formateurs' => array_values(array_filter([$row['for1'], $row['for2'], $row['for3']])),
                'salle' => (int) $row['num_salle'],
            ];
        }, $rows),
    ];
}

function lister_formateurs(?string $specialite = null): array
{
    $pdo = getDbConnection();
    $sql = 'SELECT id, nom_prenom, specialite, direction, entreprise FROM formateur';
    $params = [];

    if (!empty($specialite)) {
        $sql .= ' WHERE specialite LIKE :specialite OR nom_prenom LIKE :specialite OR direction LIKE :specialite OR entreprise LIKE :specialite';
        $params[':specialite'] = '%' . $specialite . '%';
    }

    $sql .= ' ORDER BY nom_prenom ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    return [
        'count' => count($rows),
        'formateurs' => array_map(function ($row) {
            return [
                'id' => (int) $row['id'],
                'nom' => $row['nom_prenom'],
                'specialite' => $row['specialite'],
                'direction' => $row['direction'],
                'entreprise' => $row['entreprise'],
            ];
        }, $rows),
    ];
}

function executerOutil(string $nom, array $params = []): array
{
    switch ($nom) {
        case 'lister_formations':
            return lister_formations($params['theme'] ?? null);
        case 'lister_formateurs':
            return lister_formateurs($params['specialite'] ?? null);
        default:
            throw new InvalidArgumentException('Outil inconnu : ' . $nom);
    }
}
