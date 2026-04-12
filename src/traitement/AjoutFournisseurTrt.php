<?php
/**
 * Traite le formulaire d'ajout d'un fournisseur de produits médicaux.
 * Réservé aux gestionnaires de stock et administrateurs.
 * Le nom du fournisseur est le seul champ obligatoire ; contact et email sont optionnels.
 * Redirige vers la liste des fournisseurs après traitement.
 */
session_start();

// Vérification du rôle : seuls les gestionnaires de stock et admins peuvent ajouter un fournisseur
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

// Rejet des requêtes qui ne sont pas POST
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

// Insertion uniquement si le nom est fourni
if ($nom !== '') {
    $fournisseur = new Fournisseur(null, $nom, $contact, $email);
    $repo->ajouter($fournisseur);
}

header('Location: ../../src/vue/ListeFournisseurs.php');
exit();
