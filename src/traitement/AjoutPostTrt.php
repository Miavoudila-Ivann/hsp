<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Post.php';
require_once '../../src/repository/PostRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_post) && !empty($canal) && !empty($titre) && !empty($contenu) && !empty($date_post)) {
        $post = new Post($id_post, $canal, $titre, $contenu, $date_post);
        $repo = new PostRepository($bdd);

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
