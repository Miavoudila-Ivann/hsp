<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/SalleAttente.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/OrdonnanceRepository.php';
require_once __DIR__ . '/../repository/DossierRepository.php';

use repository\OrdonnanceRepository;
use repository\DossierRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoOrdo   = new OrdonnanceRepository($bdd);
$repoDossier = new DossierRepository($bdd);

$date_emission = trim($_POST['date_emission'] ?? '');
$contenu       = trim($_POST['contenu'] ?? '');
$ref_dossier   = (int)($_POST['ref_dossier'] ?? 0);
$ref_medecin   = (int)($_POST['ref_medecin'] ?? $_SESSION['id_utilisateur'] ?? 0);

if (empty($date_emission) || empty($contenu) || $ref_dossier <= 0) {
    header('Location: ../vue/SalleAttente.php');
    exit();
}

$ordonnance = new Ordonnance(null, $date_emission, $contenu, $ref_dossier, $ref_medecin);
$repoOrdo->ajouter($ordonnance);

// Marquer le dossier comme "sorti"
$repoDossier->changerStatut($ref_dossier, 'sorti');

header('Location: ../vue/SalleAttente.php');
exit();
?>
