<?php
/**
 * Traitement de modification d'un fournisseur.
 * Réservé aux rôles gestionnaire_stock et admin.
 * Valide l'ID et le nom du fournisseur, puis met à jour l'enregistrement
 * en base via FournisseurRepository. Redirige toujours vers la liste des fournisseurs.
 */
session_start();

// Contrôle d'accès : seuls les gestionnaires de stock et admins sont autorisés
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

// Accepte uniquement les requêtes POST
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

// Récupération et nettoyage des champs du formulaire
$id_fournisseur = (int)($_POST['id_fournisseur'] ?? 0);
$nom            = trim($_POST['nom'] ?? '');
$contact        = trim($_POST['contact'] ?? '');
$email          = trim($_POST['email'] ?? '');

// Mise à jour uniquement si l'ID et le nom sont valides
if ($id_fournisseur > 0 && $nom !== '') {
    $fournisseur = new Fournisseur($id_fournisseur, $nom, $contact, $email);
    $repo->modifier($fournisseur);
}

// Retour à la liste des fournisseurs dans tous les cas
header('Location: ../../src/vue/ListeFournisseurs.php');
exit();
