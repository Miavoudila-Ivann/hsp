<?php
/**
 * Traite le formulaire d'ajout d'un produit médical au catalogue de stock.
 * Réservé aux gestionnaires de stock et administrateurs.
 * Le libellé est obligatoire et la dangerosité doit être entre 1 et 5.
 * Redirige vers la liste des produits après traitement.
 */
session_start();

// Vérification du rôle : seuls les gestionnaires de stock et admins peuvent ajouter un produit
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

// Rejet des requêtes qui ne sont pas POST
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

$libelle      = trim($_POST['libelle'] ?? '');
$description  = trim($_POST['description'] ?? '');
$dangerosite  = (int)($_POST['dangerosite'] ?? 1);
$stock_actuel = (int)($_POST['stock_actuel'] ?? 0);

// Insertion uniquement si le libellé est fourni et la dangerosité est valide (1 à 5)
if ($libelle !== '' && $dangerosite >= 1 && $dangerosite <= 5) {
    $produit = new Produit(null, $libelle, $description, $dangerosite, $stock_actuel);
    $repo->ajouter($produit);
}

header('Location: ../../src/vue/ListeProduits.php');
exit();
