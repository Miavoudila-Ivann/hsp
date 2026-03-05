<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/ListeDossiers.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DossierPriseEnCharge.php';
require_once __DIR__ . '/../repository/DossierRepository.php';

use repository\DossierRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DossierRepository($bdd);

$ref_patient    = (int)($_POST['ref_patient'] ?? 0);
$date_arrivee   = trim($_POST['date_arrivee'] ?? '');
$heure_arrivee  = trim($_POST['heure_arrivee'] ?? '');
$symptomes      = trim($_POST['symptomes'] ?? '');
$gravite        = (int)($_POST['gravite'] ?? 1);
$ref_secretaire = (int)($_POST['ref_secretaire'] ?? $_SESSION['id_utilisateur'] ?? 0);

if ($ref_patient <= 0 || empty($date_arrivee) || empty($heure_arrivee) || empty($symptomes)) {
    header('Location: ../vue/CreeDossier.php');
    exit();
}

$dossier = new DossierPriseEnCharge(
    null,
    $date_arrivee,
    $heure_arrivee,
    $symptomes,
    $gravite,
    'en_attente',
    $ref_patient,
    $ref_secretaire
);

$repo->ajouter($dossier);

header('Location: ../vue/ListeDossiers.php');
exit();
?>
