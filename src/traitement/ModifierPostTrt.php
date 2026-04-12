<?php
/**
 * Traitement de modification d'un post du forum.
 * Valide les champs obligatoires (canal, titre, contenu, date),
 * construit l'objet Post et délègue la mise à jour à PostRepository.
 * Redirige vers la liste des posts après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Post.php';
require_once '../../src/repository/PostRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($id_post) && !empty($canal) && !empty($titre) && !empty($contenu) && !empty($date_post)) {
        $post = new Post($id_post, $canal, $titre, $contenu, $date_post);
        $repo = new PostRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($post);

        if ($result) {
            // Succès : retour à la liste des posts
            header('Location: ../../vue/ListePost.php');
            exit();
        } else {
            echo "Erreur lors de la modification.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
