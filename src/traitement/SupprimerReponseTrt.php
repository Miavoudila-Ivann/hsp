<?php
/**
 * Traitement de suppression d'une réponse du forum.
 * Reçoit l'identifiant de la réponse via GET,
 * délègue la suppression à ReponseRepository,
 * puis redirige vers la liste des réponses.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/ReponseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $repo = new ReponseRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id);

    if ($result) {
        // Succès : retour à la liste des réponses
        header('Location: ../../vue/ListeReponse.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>
