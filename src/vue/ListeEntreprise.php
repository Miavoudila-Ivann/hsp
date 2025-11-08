<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Entreprise.php';
require_once '../../src/repository/EntrepriseRepository.php';

use repository\EntrepriseRepository;
use modele\Entreprise;

$database = new Bdd();
$bdd = $database->getBdd();

// Récupérer toutes les entreprises
$entrepriseRepo = new EntrepriseRepository($bdd);
$entreprises = $entrepriseRepo->findAll(); // doit retourner un tableau d'objets Entreprise
include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des entreprises</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
<h1>Liste des entreprises</h1>
<a href="AjouterEntreprise.php">Ajouter une nouvelle entreprise</a>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Rue</th>
        <th>Ville</th>
        <th>Site Web</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($entreprises)): ?>
        <?php foreach ($entreprises as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e->getId()) ?></td>
                <td><?= htmlspecialchars($e->getNom()) ?></td>
                <td><?= htmlspecialchars($e->getRue()) ?></td>
                <td><?= htmlspecialchars($e->getVille()) ?></td>
                <td><?= htmlspecialchars($e->getSiteWeb()) ?></td>
                <td>
                    <a href="ModifierEntreprise.php?id=<?= $e->getId() ?>">Modifier</a> |
                    <a href="../traitement/SupprimerEntrepriseTrt.php?id=<?= $e->getId() ?>"
                       onclick="return confirm('Voulez-vous supprimer cette entreprise ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Aucune entreprise disponible.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>
