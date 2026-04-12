<?php
/**
 * Traitement de suppression d'un événement.
 * Reçoit l'identifiant de l'événement via GET,
 * délègue la suppression à EvenementRepository,
 * puis redirige vers la page des événements.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_evenement'])) {
    $id_evenement = $_GET['id_evenement'];

    $repo = new EvenementRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_evenement);

    if ($result) {
        // Succès : retour à la page des événements
        header('Location: ../../vue/CreeEvenement.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'évenement.";
    }
} else {
    echo "ID évenement manquant.";
}
?>