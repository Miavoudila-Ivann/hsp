<?php
/**
 * Traitement de suppression d'un post du forum.
 * Reçoit l'identifiant du post via GET,
 * délègue la suppression à PostRepository,
 * puis redirige vers la liste des posts.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/PostRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_post'])) {
    $id_post = $_GET['id_post'];

    $repo = new PostRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_post);

    if ($result) {
        // Succès : retour à la liste des posts
        header('Location: ../../vue/ListePost.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID post manquant.";
}
?>
