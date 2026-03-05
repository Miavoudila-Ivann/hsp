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

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoChambre = new ChambreRepository($bdd);
$chambres_dispo = $repoChambre->findDisponibles();

$title = "Ordonner une hospitalisation";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; resize: vertical; }
    .alert-warning { background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 12px; border-radius: 5px; margin-bottom: 15px; }
</style>

<h2>Ordonner une hospitalisation</h2>

<a class="btn-retour" href="SalleAttente.php">&larr; Retour à la salle d'attente</a>

<?php if (empty($chambres_dispo)): ?>
    <div class="alert-warning">Aucune chambre disponible actuellement. Veuillez libérer une chambre avant de procéder.</div>
<?php else: ?>

<form action="../traitement/AjoutHospitalisationTrt.php" method="POST">
    <input type="hidden" name="ref_dossier"  value="<?= htmlspecialchars($id_dossier) ?>">
    <input type="hidden" name="ref_medecin"  value="<?= htmlspecialchars($_SESSION['id_utilisateur']) ?>">

    <label for="date_debut">Date de début</label>
    <input type="date" id="date_debut" name="date_debut" value="<?= date('Y-m-d') ?>" required>

    <label for="ref_chambre">Chambre</label>
    <select id="ref_chambre" name="ref_chambre" required>
        <option value="">-- Sélectionner une chambre --</option>
        <?php foreach ($chambres_dispo as $c): ?>
            <option value="<?= htmlspecialchars($c['id_chambre']) ?>">
                Chambre n°<?= htmlspecialchars($c['numero']) ?>
                <?= isset($c['nom_hopital']) ? ' - ' . htmlspecialchars($c['nom_hopital']) : '' ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="description_maladie">Description de la maladie</label>
    <textarea id="description_maladie" name="description_maladie" rows="6"
              placeholder="Description détaillée de la maladie, diagnostic, soins nécessaires..." required></textarea>

    <button type="submit">Ordonner l'hospitalisation</button>
</form>

<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
