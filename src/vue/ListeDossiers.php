<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/DossierRepository.php';

use repository\DossierRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DossierRepository($bdd);

// Récupère les dossiers en attente et en consultation
$dossiers_attente      = $repo->findByStatut('en_attente');
$dossiers_consultation = $repo->findByStatut('en_consultation');
$dossiers = array_merge($dossiers_attente, $dossiers_consultation);

$title = "Salle d'attente";
include __DIR__ . '/header.php';
?>

<style>
    .btn { display: inline-block; padding: 6px 12px; border-radius: 5px; font-weight: bold; text-decoration: none; font-size: 13px; }
    .btn-ajout { background: #28a745; color: white; margin-bottom: 15px; display: inline-block; }
    .btn:hover { opacity: 0.85; }
    .statut-attente      { color: #856404; background: #fff3cd; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .statut-consultation { color: #0c5460; background: #d1ecf1; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .gravite { font-weight: bold; }
</style>

<h2>Salle d'attente</h2>

<a class="btn btn-ajout" href="CreeDossier.php">+ Nouvelle prise en charge</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Date arrivée</th>
            <th>Heure</th>
            <th>Symptômes</th>
            <th>Gravité</th>
            <th>Statut</th>
            <th>Secrétaire</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dossiers as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['id_dossier']) ?></td>
                <td><?= htmlspecialchars($d['nom_patient']) . ' ' . htmlspecialchars($d['prenom_patient']) ?></td>
                <td><?= htmlspecialchars($d['date_arrivee']) ?></td>
                <td><?= htmlspecialchars($d['heure_arrivee']) ?></td>
                <td><?= htmlspecialchars($d['symptomes']) ?></td>
                <td class="gravite">
                    <?php
                    $g = (int)$d['gravite'];
                    echo $g . '/5 ';
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $g ? '&#9733;' : '&#9734;';
                    }
                    ?>
                </td>
                <td>
                    <?php if ($d['statut'] === 'en_attente'): ?>
                        <span class="statut-attente">En attente</span>
                    <?php else: ?>
                        <span class="statut-consultation">En consultation</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($d['nom_secretaire'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($dossiers)): ?>
            <tr><td colspan="8" style="text-align:center;">Aucun dossier en cours.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
