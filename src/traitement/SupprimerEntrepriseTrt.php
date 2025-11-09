<?php
// SupprimerEntrepriseTrt.php
session_start();

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EntrepriseRepository.php';

use repository\EntrepriseRepository;

// Vérifie que l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ListeEntreprise.php');
    exit;
}

$id = (int)$_GET['id'];

$database = new Bdd();
$bdd = $database->getBdd();

$repo = new EntrepriseRepository($bdd);

if ($repo->supprimerEntreprise($id)) {
    // Succès
    header('Location: ../vue/ListeEntreprise.php?success=1');
    exit;
} else {
    // Erreur
    header('Location: ../vue/ListeEntreprise.php?error=1');
    exit;
}
?>
