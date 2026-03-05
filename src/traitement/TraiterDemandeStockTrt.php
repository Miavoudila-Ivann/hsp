<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../src/vue/ListeDemandesStock.php');
    exit();
}

require_once __DIR__ . '/../../src/bdd/Bdd.php';
require_once __DIR__ . '/../../src/repository/DemandeStockRepository.php';

use repository\DemandeStockRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DemandeStockRepository($bdd);

$id_demande = (int)($_POST['id_demande'] ?? 0);
$statut     = trim($_POST['statut'] ?? '');

$statutsValides = ['acceptee', 'refusee'];

if ($id_demande > 0 && in_array($statut, $statutsValides)) {
    // Changer le statut de la demande
    $repo->changerStatut($id_demande, $statut);

    // Si acceptée : décrémenter le stock du produit
    if ($statut === 'acceptee') {
        $demande = $repo->findById($id_demande);
        if ($demande) {
            $stmt = $bdd->prepare(
                "UPDATE produit SET stock_actuel = stock_actuel - :quantite WHERE id_produit = :id_produit"
            );
            $stmt->execute([
                ':quantite'   => (int)$demande->getQuantite(),
                ':id_produit' => (int)$demande->getRefProduit(),
            ]);
        }
    }
}

header('Location: ../../src/vue/ListeDemandesStock.php');
exit();
