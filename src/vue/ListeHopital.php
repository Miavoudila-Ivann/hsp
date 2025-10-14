<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';
require_once '../../src/modele/Hopital.php';

use repository\HopitalRepository;

$pdo = (new \Bdd())->getBdd();
$repo = new HopitalRepository($pdo);
$hopitaux = $pdo->query('SELECT * FROM hopital')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des hôpitaux</title>
    <style>
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f3f3f3;
        }
        a.button {
            padding: 6px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        a.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<h1 style="text-align:center;">Liste des hôpitaux</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Adresse</th>
        <th>Ville</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($hopitaux as $hopital): ?>
        <tr>
            <td><?= htmlspecialchars($hopital['id_hopital']) ?></td>
            <td><?= htmlspecialchars($hopital['nom']) ?></td>
            <td><?= htmlspecialchars($hopital['adresse_hopital']) ?></td>
            <td><?= htmlspecialchars($hopital['ville_hopital']) ?></td>
            <td>
                <a class="button" href="ModifierHopital.php?id=<?= $hopital['id_hopital'] ?>">Modifier</a>
                <a class="button delete" href="SupprimerHopital.php?id=<?= $hopital['id_hopital'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet hôpital ?');">Supprimer</a>
                <a class="button" href="PageCreeHopital.php">Ajouter</a>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
