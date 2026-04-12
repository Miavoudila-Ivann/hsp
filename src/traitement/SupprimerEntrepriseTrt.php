<?php
/**
 * Traitement de suppression d'une entreprise.
 * Reçoit l'identifiant de l'entreprise via GET, vérifie sa présence,
 * délègue la suppression à EntrepriseRepository,
 * puis redirige avec un paramètre success ou error selon le résultat.
 */
session_start();

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EntrepriseRepository.php';

use repository\EntrepriseRepository;

// Vérifie que l'ID est fourni et non vide
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ListeEntreprise.php');
    exit;
}

$id = (int)$_GET['id'];

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new EntrepriseRepository($bdd);

// Suppression en base de données et redirection selon le résultat
if ($repo->supprimerEntreprise($id)) {
    header('Location: ../vue/ListeEntreprise.php?success=1');
    exit;
} else {
    header('Location: ../vue/ListeEntreprise.php?error=1');
    exit;
}
?>
