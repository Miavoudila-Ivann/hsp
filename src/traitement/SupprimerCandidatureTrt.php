<?php
/**
 * Traitement de suppression d'une candidature.
 * Reçoit l'identifiant de la candidature via GET,
 * délègue la suppression à CandidatureRepository,
 * puis redirige vers la liste des candidatures.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/CandidatureRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_candidature = $_GET['id'];
    $repo = new CandidatureRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_candidature);

    if ($result) {
        // Succès : retour à la liste des candidatures
        header('Location: ../../vue/ListeCandidature.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de la candidature.";
    }
}
