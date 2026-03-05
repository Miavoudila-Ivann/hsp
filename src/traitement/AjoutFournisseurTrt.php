<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../src/vue/ListeFournisseurs.php');
    exit();
}

require_once __DIR__ . '/../../src/bdd/Bdd.php';
require_once __DIR__ . '/../../src/modele/Fournisseur.php';
require_once __DIR__ . '/../../src/repository/FournisseurRepository.php';

use repository\FournisseurRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new FournisseurRepository($bdd);

$nom     = trim($_POST['nom'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$email   = trim($_POST['email'] ?? '');

if ($nom !== '') {
    $fournisseur = new Fournisseur(null, $nom, $contact, $email);
    $repo->ajouter($fournisseur);
}

header('Location: ../../src/vue/ListeFournisseurs.php');
exit();
