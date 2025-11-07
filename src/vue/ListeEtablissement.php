<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';
require_once '../../src/modele/Etablissement.php';

use repository\EtablissementRepository;
include __DIR__ . '/header.php';
$pdo = (new \Bdd())->getBdd();
$repo = new EtablissementRepository($pdo);
$etablissements = $pdo->query('SELECT * FROM etablissement')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Établissements</title>
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
<h1 style="text-align:center;">Liste des Établissements</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom de l'établissement</th>
        <th>Adresse</th>
        <th>Site Web</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($etablissements as $etablissement): ?>
        <tr>
            <td><?= htmlspecialchars($etablissement['id_etablissement']) ?></td>
            <td><?= htmlspecialchars($etablissement['nom_etablissement']) ?></td>
            <td><?= htmlspecialchars($etablissement['adresse_etablissement']) ?></td>
            <td><?= htmlspecialchars($etablissement['site_web_etablissement']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
<?php include __DIR__ . '/footer.php'; ?>