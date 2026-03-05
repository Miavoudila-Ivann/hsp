<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/PatientRepository.php';

use repository\PatientRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoPatient = new PatientRepository($bdd);
$patients = $repoPatient->findAll();

$title = "Nouvelle prise en charge";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; resize: vertical; }
</style>

<h2>Nouvelle prise en charge</h2>

<a class="btn-retour" href="ListeDossiers.php">&larr; Retour à la salle d'attente</a>

<form action="../traitement/AjoutDossierTrt.php" method="POST">

    <label for="ref_patient">Patient</label>
    <select id="ref_patient" name="ref_patient" required>
        <option value="">-- Sélectionner un patient --</option>
        <?php foreach ($patients as $p): ?>
            <option value="<?= htmlspecialchars($p['id_patient']) ?>">
                <?= htmlspecialchars($p['nom']) . ' ' . htmlspecialchars($p['prenom']) ?>
                (<?= htmlspecialchars($p['num_secu']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label for="date_arrivee">Date d'arrivée</label>
    <input type="date" id="date_arrivee" name="date_arrivee" value="<?= date('Y-m-d') ?>" required>

    <label for="heure_arrivee">Heure d'arrivée</label>
    <input type="time" id="heure_arrivee" name="heure_arrivee" value="<?= date('H:i') ?>" required>

    <label for="symptomes">Symptômes</label>
    <textarea id="symptomes" name="symptomes" rows="4" placeholder="Décrivez les symptômes du patient..." required></textarea>

    <label for="gravite">Gravité</label>
    <select id="gravite" name="gravite" required>
        <option value="">-- Niveau de gravité --</option>
        <option value="1">1 - Très faible</option>
        <option value="2">2 - Faible</option>
        <option value="3">3 - Moyen</option>
        <option value="4">4 - Élevé</option>
        <option value="5">5 - Critique</option>
    </select>

    <input type="hidden" name="ref_secretaire" value="<?= htmlspecialchars($_SESSION['id_utilisateur']) ?>">

    <button type="submit">Enregistrer la prise en charge</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
