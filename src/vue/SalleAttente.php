<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/DossierRepository.php';

use repository\DossierRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DossierRepository($bdd);

// Prendre en charge un dossier : passe à 'en_consultation'
if (isset($_GET['prise_en_charge'])) {
    $idDossier = (int)$_GET['prise_en_charge'];
    $repo->changerStatut($idDossier, 'en_consultation');
    header('Location: SalleAttente.php');
    exit();
}

$dossiers_attente      = $repo->findByStatut('en_attente');
$dossiers_consultation = $repo->findByStatut('en_consultation');

$title = "Salle d'attente - Médecin";
include __DIR__ . '/header.php';
?>

<style>
    .btn { display: inline-block; padding: 6px 12px; border-radius: 5px; font-weight: bold; text-decoration: none; font-size: 13px; margin: 2px; }
    .btn-prise    { background: #007bff; color: white; }
    .btn-ordo     { background: #17a2b8; color: white; }
    .btn-hospit   { background: #6f42c1; color: white; }
    .btn:hover    { opacity: 0.85; }
    .statut-attente      { color: #856404; background: #fff3cd; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .statut-consultation { color: #0c5460; background: #d1ecf1; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .gravite { font-weight: bold; }
    h3 { color: #555; margin-top: 30px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
</style>

<h2>Salle d'attente &mdash; Médecin</h2>

<h3>Patients en attente</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Symptômes</th>
            <th>Gravité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dossiers_attente as $d): ?>
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
                    <a class="btn btn-prise"
                       href="SalleAttente.php?prise_en_charge=<?= $d['id_dossier'] ?>"
                       onclick="return confirm('Prendre ce patient en charge ?')">Prendre en charge</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($dossiers_attente)): ?>
            <tr><td colspan="7" style="text-align:center;">Aucun patient en attente.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3>Patients en consultation</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Symptômes</th>
            <th>Gravité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dossiers_consultation as $d): ?>
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
                    <a class="btn btn-ordo" href="CreeOrdonnance.php?id_dossier=<?= $d['id_dossier'] ?>">Ordonnance</a>
                    <a class="btn btn-hospit" href="CreeHospitalisation.php?id_dossier=<?= $d['id_dossier'] ?>">Hospitaliser</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($dossiers_consultation)): ?>
            <tr><td colspan="7" style="text-align:center;">Aucun patient en consultation.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
