<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/HopitalRepository.php';

use repository\HopitalRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoHopital = new HopitalRepository($bdd);
$hopitaux = $repoHopital->findAll();

$title = "Ajouter une chambre";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
</style>

<h2>Ajouter une chambre</h2>

<a class="btn-retour" href="ListeChambres.php">&larr; Retour à la liste</a>

<form action="../traitement/AjoutChambreTrt.php" method="POST">

    <label for="numero">Numéro de chambre</label>
    <input type="text" id="numero" name="numero" placeholder="Ex : 101, A12..." required>

    <label for="ref_hopital">Hôpital</label>
    <select id="ref_hopital" name="ref_hopital" required>
        <option value="">-- Sélectionner un hôpital --</option>
        <?php foreach ($hopitaux as $h): ?>
            <option value="<?= htmlspecialchars($h['id_hopital']) ?>">
                <?= htmlspecialchars($h['nom']) ?>
                (<?= htmlspecialchars($h['ville_hopital']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Ajouter la chambre</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
