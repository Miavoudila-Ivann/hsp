<?php
/**
 * Traite le formulaire de création d'un post sur le forum.
 * Valide les champs obligatoires (canal, titre, contenu, date) et insère le post en base.
 * Redirige vers la liste des posts après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Post.php';
require_once '../../src/repository/PostRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($id_post) && !empty($canal) && !empty($titre) && !empty($contenu) && !empty($date_post)) {
        $post = new Post($id_post, $canal, $titre, $contenu, $date_post);
        $repo = new PostRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($post);

        if ($result) {
            header('Location: ../../vue/ListePost.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
