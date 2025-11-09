<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/repository/OffreRepository.php';
require_once '../../src/repository/EntrepriseRepository.php'; // si tu as un repo pour les entreprises

use repository\OffreRepository;
use repository\EntrepriseRepository;

$database = new Bdd();
$bdd = $database->getBdd();

// Récupérer toutes les offres
$offreRepo = new OffreRepository($bdd);
$offres = $offreRepo->findAll(); // tableau d'objets Offre

// Récupérer toutes les entreprises pour créer un map id => nom
$entrepriseRepo = new EntrepriseRepository($bdd);
$entreprises = $entrepriseRepo->findAll(); // tableau d'objets Entreprise
$entreprisesMap = [];
foreach ($entreprises as $e) {
    $entreprisesMap[$e->getId()] = $e->getNom();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des offres</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
<h1>Liste des offres</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Mission</th>
        <th>Salaire</th>
        <th>Type d'offre</th>
        <th>État</th>
        <th>Entreprise</th>
        <th>Date de publication</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($offres as $o): ?>
        <tr>
            <td><?= htmlspecialchars($o->getIdOffre()) ?></td>
            <td><?= htmlspecialchars($o->getTitre()) ?></td>
            <td><?= htmlspecialchars($o->getDescription()) ?></td>
            <td><?= htmlspecialchars($o->getMission()) ?></td>
            <td><?= htmlspecialchars($o->getSalaire()) ?></td>
            <td><?= htmlspecialchars($o->getTypeOffre()) ?></td>
            <td><?= htmlspecialchars($o->getEtat()) ?></td>
            <td><?= htmlspecialchars($entreprisesMap[$o->getRefEntreprise()] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($o->getDatePublication()) ?></td>
            <td>
                <a href="ModifierOffre.php?id=<?= $o->getIdOffre() ?>">Modifier</a> |
                <a href="../traitement/SupprimerOffreTrt.php?id=<?= $o->getIdOffre() ?>" onclick="return confirm('Voulez-vous supprimer cette offre ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php if (empty($offres)): ?>
        <tr>
            <td colspan="11">Aucune offre disponible.</td>
        </tr>
    <?php endif; ?>
    <a href="../vue/AjouterOffre.php">Retour</a>
    </tbody>
</table>
</body>
</html>
