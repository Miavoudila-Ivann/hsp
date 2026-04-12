<?php
/**
 * Traitement de suppression d'une offre d'emploi.
 * Reçoit l'identifiant de l'offre via GET,
 * délègue la suppression à OffreRepository,
 * puis redirige vers la liste des offres.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    $repo = new OffreRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_offre);

    if ($result) {
        // Succès : retour à la liste des offres
        header('Location: ../../vue/ListeOffre.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID offre manquant.";
}
?>
