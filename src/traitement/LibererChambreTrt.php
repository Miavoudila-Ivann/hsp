<?php
/**
 * Libère une chambre hospitalière après la sortie d'un patient.
 * Réservé aux médecins et administrateurs.
 * Reçoit l'identifiant de la chambre via GET et la marque comme disponible.
 * Redirige vers la liste des chambres après traitement.
 */
session_start();

// Vérification du rôle : seuls les médecins et admins peuvent libérer une chambre
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ChambreRepository($bdd);

// Mise à jour de la disponibilité si un identifiant de chambre est fourni
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $repo->setDisponible($id, true);
}

header('Location: ../vue/ListeChambres.php');
exit();
?>
