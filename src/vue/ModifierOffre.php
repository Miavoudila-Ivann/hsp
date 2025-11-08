<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';
require_once '../../src/repository/EntrepriseRepository.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/modele/Entreprise.php';

use repository\OffreRepository;
use repository\EntrepriseRepository;

$database = new Bdd();
$bdd = $database->getBdd();

// Récupération de l'offre à modifier
$repoOffre = new OffreRepository($bdd);
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID de l'offre manquant.");
}

$offre = $repoOffre->findById((int)$id);
if (!$offre) {
    die("Offre introuvable.");
}

// Récupération des entreprises pour le select
$repoEntreprise = new EntrepriseRepository($bdd);
$entreprises = $repoEntreprise->findAll();

// Fonction utilitaire pour sécuriser les sorties HTML
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

include __DIR__ . '/header.php';
?>

<h2>Modifier l'offre</h2>
<form action="../traitement/ModifierOffreTrt.php" method="POST">
    <input type="hidden" name="id_offre" value="<?= $offre->getIdOffre() ?>">

    <label>Titre :</label>
    <input type="text" name="titre" value="<?= h($offre->getTitre()) ?>" required>

    <label>Description :</label>
    <textarea name="description" required><?= h($offre->getDescription()) ?></textarea>

    <label>Mission :</label>
    <textarea name="mission" required><?= h($offre->getMission()) ?></textarea>

    <label>Salaire :</label>
    <input type="text" name="salaire" value="<?= h($offre->getSalaire()) ?>" required>

    <label>Type d'offre :</label>
    <input type="text" name="type_offre" value="<?= h($offre->getTypeOffre()) ?>" required>

    <label>État :</label>
    <select name="etat" required>
        <option value="ouverte" <?= $offre->getEtat() === 'ouverte' ? 'selected' : '' ?>>Ouverte</option>
        <option value="fermée" <?= $offre->getEtat() === 'fermée' ? 'selected' : '' ?>>Fermée</option>
    </select>

    <label>Entreprise :</label>
    <select name="ref_entreprise" required>
        <?php foreach ($entreprises as $e): ?>
            <option value="<?= $e->getId() ?>" <?= $offre->getRefEntreprise() == $e->getId() ? 'selected' : '' ?>>
                <?= h($e->getNom()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>ID Utilisateur :</label>
    <input type="number" name="ref_utilisateur" value="<?= h($offre->getRefUtilisateur()) ?>" required>

    <label>Date de publication :</label>
    <input type="date" name="date_publication" value="<?= h($offre->getDatePublication()) ?>" required>

    <button type="submit" name="ok">Modifier</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
