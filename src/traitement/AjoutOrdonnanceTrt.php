<?php
/**
 * Traite le formulaire de création d'une ordonnance médicale.
 * Réservé aux médecins et administrateurs.
 * Insère l'ordonnance en base et marque automatiquement le dossier associé
 * comme "sorti" (consultation terminée). Redirige vers la salle d'attente.
 */
session_start();

// Vérification du rôle : seuls les médecins et admins peuvent émettre une ordonnance
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

// Rejet des requêtes qui ne sont pas POST
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
$repoOrdo    = new OrdonnanceRepository($bdd);
$repoDossier = new DossierRepository($bdd);

$date_emission = trim($_POST['date_emission'] ?? '');
$contenu       = trim($_POST['contenu'] ?? '');
$ref_dossier   = (int)($_POST['ref_dossier'] ?? 0);
// Identifiant du médecin : issu du POST ou de la session en cours
$ref_medecin   = (int)($_POST['ref_medecin'] ?? $_SESSION['id_utilisateur'] ?? 0);

// Validation : date d'émission, contenu et référence du dossier sont obligatoires
if (empty($date_emission) || empty($contenu) || $ref_dossier <= 0) {
    header('Location: ../vue/SalleAttente.php');
    exit();
}

// Enregistrement de l'ordonnance
$ordonnance = new Ordonnance(null, $date_emission, $contenu, $ref_dossier, $ref_medecin);
$repoOrdo->ajouter($ordonnance);

// Marquer le dossier comme "sorti" : la consultation est terminée
$repoDossier->changerStatut($ref_dossier, 'sorti');

header('Location: ../vue/SalleAttente.php');
exit();
?>
