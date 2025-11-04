<?php
// ✅ Chemin correct : un seul ".." pour remonter de "vue/" vers "hsp/", puis entrer dans "src/"
require_once '../src/bdd/Bdd.php';
require_once '../src/repository/MedecinRepository.php';

$db = new Bdd();
$repo = new MedecinRepository($db->getBdd());
$medecins = $repo->trouverTous();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Médecins</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 14px;
        }
        .btn-ajout { background-color: #28a745; }
        .btn-modif { background-color: #17a2b8; }
        .btn-suppr { background-color: #dc3545; }
        .actions { text-align: center; }
    </style>
</head>
<body>

<h2>Liste des Médecins</h2>

<a href="CreeMedecin.php" class="btn btn-ajout">Ajouter un médecin</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Spécialité</th>
        <th>Hôpital</th>
        <th>Établissement</th>
        <th class="actions">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($medecins)): ?>
        <?php foreach ($medecins as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['id_medecin']) ?></td>
                <td><?= htmlspecialchars($m['specialite'] ?? '—') ?></td>
                <td><?= htmlspecialchars($m['hopital'] ?? '—') ?></td>
                <td><?= htmlspecialchars($m['etablissement'] ?? '—') ?></td>
                <td class="actions">
                    <a href="ModifierMedecin.php?id_medecin=<?= $m['id_medecin'] ?>" class="btn btn-modif">Modifier</a>
                    <a href="SupprimerMedecin.php?id_medecin=<?= $m['id_medecin'] ?>" class="btn btn-suppr">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5" style="text-align:center;">Aucun médecin trouvé.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>