<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';
require_once '../../src/repository/EntrepriseRepository.php';

use repository\OffreRepository;
use repository\EntrepriseRepository;

$database = new Bdd();
$bdd = $database->getBdd();

// --- Récupération des offres ---
$repoOffre = new OffreRepository($bdd);
$offres = $repoOffre->findAll();

// --- Récupération des entreprises pour le select et affichage nom ---
$repoEntreprise = new EntrepriseRepository($bdd);
$entreprises = $repoEntreprise->findAll();

// Fonction utilitaire pour sécuriser les sorties HTML
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
// Créer un tableau id_entreprise => nom pour pouvoir afficher le nom de l'entreprise
$entrepriseRepo = new EntrepriseRepository($bdd);
$entreprises = $entrepriseRepo->findAll(); // tableau d'objets Entreprise
$entreprisesMap = [];
foreach ($entreprises as $e) {
    $entreprisesMap[$e->getId()] = $e->getNom();
}



include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Offre</title>
</head>
<body>
<h2>Ajouter une offre</h2>
<form action="../traitement/AjouterOffreTrt.php" method="POST">
    <label>Titre :</label>
    <input type="text" name="titre" required>

    <label>Description :</label>
    <textarea name="description" required></textarea>

    <label>Mission :</label>
    <textarea name="mission" required></textarea>

    <label>Salaire :</label>
    <input type="text" name="salaire" required>

    <label>Type d'offre :</label>
    <input type="text" name="type_offre" required>

    <label>État :</label>
    <select name="etat" required>
        <option value="ouverte">Ouverte</option>
        <option value="fermée">Fermée</option>
    </select>

    <label>Entreprise :</label>
    <select name="ref_entreprise" required>
        <?php foreach ($entreprises as $e): ?>
            <option value="<?= $e->getId() ?>"><?= htmlspecialchars($e->getNom()) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Date de publication :</label>
    <input type="date" name="date_publication" required>

    <button type="submit" name="ok">Ajouter</button><br><br>
</form>
<form action="../../index.php" method="get">
    <button type="submit">retour à l'acceuil</button>
</form>
<?php include __DIR__ . '/footer.php'; ?>
<section>
    <h2>Offres existantes</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Mission</th>
            <th>Salaire</th>
            <th>Type</th>
            <th>État</th>
            <th>Entreprise</th>
            <th>Date Publication</th>
            <th>Actions</th>
        </tr>
        <?php foreach($offres as $o): ?>
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
                    <a href="../vue/ModifierOffre.php?id=<?= $o->getIdOffre() ?>">Modifier</a> |
                    <a href="../traitement/SupprimerOffreTrt.php?id=<?= $o->getIdOffre() ?>" onclick="return confirm('Voulez-vous supprimer cette offre ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
</body>
</html>

