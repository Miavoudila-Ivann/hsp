<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/repository/SpecialiteRepository.php';

$db = new Bdd();
$repo = new SpecialiteRepository($db->getBdd());
$specialites = $repo->trouverTous();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Spécialités</title>
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

<h2>Liste des Spécialités</h2>

<a href="CreeSpecialite.php" class="btn btn-ajout">Ajouter une spécialité</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Libellé</th>
        <th class="actions">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($specialites)): ?>
        <?php foreach ($specialites as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['id_specialite']) ?></td>
                <td><?= htmlspecialchars($s['libelle']) ?></td>
                <td class="actions">
                    <a href="ModifierSpecialite.php?id_specialite=<?= $s['id_specialite'] ?>" class="btn btn-modif">Modifier</a>
                    <a href="SupprimerSpecialite.php?id_specialite=<?= $s['id_specialite'] ?>" class="btn btn-suppr">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="3" style="text-align:center;">Aucune spécialité.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>