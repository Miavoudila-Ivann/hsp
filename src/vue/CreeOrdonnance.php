<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

if (!isset($_GET['id_dossier'])) {
    header('Location: SalleAttente.php');
    exit();
}

$id_dossier = (int)$_GET['id_dossier'];

$title = "Émettre une ordonnance";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; resize: vertical; }
</style>

<h2>Émettre une ordonnance</h2>

<a class="btn-retour" href="SalleAttente.php">&larr; Retour à la salle d'attente</a>

<form action="../traitement/AjoutOrdonnanceTrt.php" method="POST">
    <input type="hidden" name="ref_dossier" value="<?= htmlspecialchars($id_dossier) ?>">
    <input type="hidden" name="ref_medecin"  value="<?= htmlspecialchars($_SESSION['id_utilisateur']) ?>">

    <label for="date_emission">Date d'émission</label>
    <input type="date" id="date_emission" name="date_emission" value="<?= date('Y-m-d') ?>" required>

    <label for="contenu">Contenu de l'ordonnance</label>
    <textarea id="contenu" name="contenu" rows="8"
              placeholder="Médicaments, posologie, durée du traitement..." required></textarea>

    <button type="submit">Émettre l'ordonnance</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
