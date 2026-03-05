<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/PatientRepository.php';

use repository\PatientRepository;

if (!isset($_GET['id'])) {
    header('Location: ListePatients.php');
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new PatientRepository($bdd);

$patient = $repo->findById((int)$_GET['id']);
if ($patient === null) {
    header('Location: ListePatients.php');
    exit();
}

$title = "Modifier un patient";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; resize: vertical; }
</style>

<h2>Modifier un patient</h2>

<a class="btn-retour" href="ListePatients.php">&larr; Retour à la liste</a>

<form action="../traitement/ModifierPatientTrt.php" method="POST">
    <input type="hidden" name="id_patient" value="<?= htmlspecialchars($patient->getIdPatient()) ?>">

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($patient->getNom()) ?>" required>

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($patient->getPrenom()) ?>" required>

    <label for="num_secu">Numéro de sécurité sociale</label>
    <input type="text" id="num_secu" name="num_secu" value="<?= htmlspecialchars($patient->getNumSecu()) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($patient->getEmail()) ?>">

    <label for="telephone">Téléphone</label>
    <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($patient->getTelephone()) ?>">

    <label for="adresse">Adresse</label>
    <textarea id="adresse" name="adresse" rows="2"><?= htmlspecialchars($patient->getAdresse()) ?></textarea>

    <button type="submit">Enregistrer les modifications</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
