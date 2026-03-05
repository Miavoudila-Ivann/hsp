<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/DemanderStock.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DemandeStock.php';
require_once __DIR__ . '/../repository/DemandeStockRepository.php';

use repository\DemandeStockRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DemandeStockRepository($bdd);

$ref_produit  = (int)($_POST['ref_produit'] ?? 0);
$quantite     = (int)($_POST['quantite'] ?? 0);
$ref_medecin  = (int)($_POST['ref_medecin'] ?? $_SESSION['id_utilisateur'] ?? 0);
$date_demande = trim($_POST['date_demande'] ?? date('Y-m-d'));

if ($ref_produit <= 0 || $quantite <= 0) {
    header('Location: ../vue/DemanderStock.php');
    exit();
}

$demande = new DemandeStock(null, $quantite, 'en_attente', $date_demande, $ref_produit, $ref_medecin);
$repo->ajouter($demande);

header('Location: ../vue/DemanderStock.php');
exit();
?>
