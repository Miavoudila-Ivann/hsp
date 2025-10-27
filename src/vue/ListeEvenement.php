<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';
require_once '../../src/modele/Evenement.php';

use repository\EvenementRepository;

$pdo = (new \Bdd())->getBdd();
$repo = new EvenementRepository($pdo);
$evenements = $pdo->query('SELECT * FROM evenement')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Événements</title>
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
<h1 style="text-align:center;">Liste des Événements</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom de l'événement</th>
        <th>Date</th>
        <th>Lieu</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($evenements as $evenement): ?>
        <tr>
            <td><?= htmlspecialchars($evenement['id_evenement'] ?? 'Inconnu') ?></td>
            <td><?= htmlspecialchars($evenement['nom_evenement'] ?? 'Nom non disponible') ?></td>
            <td><?= htmlspecialchars($evenement['date_evenement'] ?? 'Date non disponible') ?></td>
            <td><?= htmlspecialchars($evenement['lieu_evenement'] ?? 'Lieu non disponible') ?></td>
            <td>
                <a class="button" href="ModifierEvenement.php?id=<?= $evenement['id_evenement'] ?>">Modifier</a>
                <a class="button delete" href="SupprimerEvenement.php?id=<?= $evenement['id_evenement'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet événement ?');">Supprimer</a>
                <a class="button" href="PageCreeEvenement.php">Ajouter</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
