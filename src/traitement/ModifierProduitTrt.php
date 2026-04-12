<?php
/**
 * Traitement de modification d'un produit de stock.
 * Réservé aux rôles gestionnaire_stock et admin.
 * Valide le libellé, la dangérosité (valeur entre 1 et 5) et l'ID du produit,
 * puis met à jour l'enregistrement via ProduitRepository.
 * Redirige toujours vers la liste des produits.
 */
session_start();

// Contrôle d'accès : seuls les gestionnaires de stock et admins sont autorisés
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

// Accepte uniquement les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../src/vue/ListeProduits.php');
    exit();
}

require_once __DIR__ . '/../../src/bdd/Bdd.php';
require_once __DIR__ . '/../../src/modele/Produit.php';
require_once __DIR__ . '/../../src/repository/ProduitRepository.php';

use repository\ProduitRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ProduitRepository($bdd);

// Récupération et typage des champs du formulaire
$id_produit   = (int)($_POST['id_produit'] ?? 0);
$libelle      = trim($_POST['libelle'] ?? '');
$description  = trim($_POST['description'] ?? '');
$dangerosite  = (int)($_POST['dangerosite'] ?? 1);
$stock_actuel = (int)($_POST['stock_actuel'] ?? 0);

// Mise à jour uniquement si l'ID, le libellé et la dangérosité sont valides
if ($id_produit > 0 && $libelle !== '' && $dangerosite >= 1 && $dangerosite <= 5) {
    $produit = new Produit($id_produit, $libelle, $description, $dangerosite, $stock_actuel);
    $repo->modifier($produit);
}

// Retour à la liste des produits dans tous les cas
header('Location: ../../src/vue/ListeProduits.php');
exit();
