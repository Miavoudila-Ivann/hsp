<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

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

if ($libelle !== '' && $dangerosite >= 1 && $dangerosite <= 5) {
    $produit = new Produit(null, $libelle, $description, $dangerosite, $stock_actuel);
    $repo->ajouter($produit);
}

header('Location: ../../src/vue/ListeProduits.php');
exit();
