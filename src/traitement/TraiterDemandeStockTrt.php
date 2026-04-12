<?php
/**
 * Traitement d'une demande de stock (acceptation ou refus).
 * Réservé aux rôles gestionnaire_stock et admin.
 * Valide le statut reçu (acceptee / refusee) et met à jour la demande en base.
 * Si la demande est acceptée, décrémente le stock du produit concerné
 * de la quantité demandée via une requête directe.
 * Redirige toujours vers la liste des demandes de stock.
 */
session_start();

// Contrôle d'accès : seuls les gestionnaires de stock et admins sont autorisés
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: ../../src/vue/Connexion.php');
    exit();
}

// Accepte uniquement les requêtes POST
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

// Récupération et validation des champs du formulaire
$id_demande = (int)($_POST['id_demande'] ?? 0);
$statut     = trim($_POST['statut'] ?? '');

// Seuls ces deux statuts sont autorisés
$statutsValides = ['acceptee', 'refusee'];

if ($id_demande > 0 && in_array($statut, $statutsValides)) {
    // Mise à jour du statut de la demande en base
    $repo->changerStatut($id_demande, $statut);

    // Si acceptée : décrémenter le stock du produit concerné
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

// Retour à la liste des demandes dans tous les cas
header('Location: ../../src/vue/ListeDemandesStock.php');
exit();
