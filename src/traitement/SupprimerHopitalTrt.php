<?php
/**
 * Traitement de suppression d'un hôpital.
 * Reçoit l'identifiant de l'hôpital via GET,
 * délègue la suppression à HopitalRepository,
 * puis redirige vers la page des hôpitaux.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_hopital'])) {
    $id_hopital = $_GET['id_hopital'];

    $repo = new HopitalRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_hopital);

    if ($result) {
        // Succès : retour à la page des hôpitaux
        header('Location: ../vue/CreeHopital.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'hopital.";
    }
} else {
    echo "ID hopital manquant.";
}
