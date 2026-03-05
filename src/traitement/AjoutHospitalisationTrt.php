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
require_once __DIR__ . '/../modele/Hospitalisation.php';
require_once __DIR__ . '/../repository/HospitalisationRepository.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';
require_once __DIR__ . '/../repository/DossierRepository.php';

use repository\HospitalisationRepository;
use repository\ChambreRepository;
use repository\DossierRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoHospit  = new HospitalisationRepository($bdd);
$repoChambre = new ChambreRepository($bdd);
$repoDossier = new DossierRepository($bdd);

$date_debut          = trim($_POST['date_debut'] ?? '');
$description_maladie = trim($_POST['description_maladie'] ?? '');
$ref_dossier         = (int)($_POST['ref_dossier'] ?? 0);
$ref_chambre         = (int)($_POST['ref_chambre'] ?? 0);
$ref_medecin         = (int)($_POST['ref_medecin'] ?? $_SESSION['id_utilisateur'] ?? 0);

if (empty($date_debut) || empty($description_maladie) || $ref_dossier <= 0 || $ref_chambre <= 0) {
    header('Location: ../vue/SalleAttente.php');
    exit();
}

$hospitalisation = new Hospitalisation(
    null,
    $date_debut,
    $description_maladie,
    $ref_dossier,
    $ref_chambre,
    $ref_medecin
);

$repoHospit->ajouter($hospitalisation);

// Marquer la chambre comme occupée
$repoChambre->setDisponible($ref_chambre, false);

// Changer le statut du dossier à 'hospitalise'
$repoDossier->changerStatut($ref_dossier, 'hospitalise');

header('Location: ../vue/SalleAttente.php');
exit();
?>
