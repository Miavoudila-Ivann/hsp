<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/PostRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_post'])) {
    $id_post = $_GET['id_post'];

    $repo = new PostRepository($bdd);
    $result = $repo->supprimer($id_post);

    if ($result) {
        header('Location: ../../vue/ListePost.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID post manquant.";
}
?>
