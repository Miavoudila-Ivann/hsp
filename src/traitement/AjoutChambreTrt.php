<?php
/**
 * Traite le formulaire de création d'une chambre hospitalière.
 * Réservé aux médecins et administrateurs.
 * Valide le numéro et l'hôpital de rattachement, puis insère la chambre (disponible par défaut).
 * Redirige vers la liste des chambres après succès.
 */
session_start();

// Vérification du rôle : seuls les médecins et admins peuvent créer une chambre
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

// Rejet des requêtes qui ne sont pas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/ListeChambres.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Chambre.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ChambreRepository($bdd);

$numero     = trim($_POST['numero'] ?? '');
$ref_hopital = (int)($_POST['ref_hopital'] ?? 0);

// Validation : numéro et hôpital obligatoires
if (empty($numero) || $ref_hopital <= 0) {
    header('Location: ../vue/CreeChambre.php');
    exit();
}

// Création de la chambre avec disponibilité à 1 (disponible)
$chambre = new Chambre(null, $numero, 1, $ref_hopital);
$repo->ajouter($chambre);

header('Location: ../vue/ListeChambres.php');
exit();
?>
