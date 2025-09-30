<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Reponse.php';
require_once '../../src/repository/ReponseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($idReponse) && !empty($contenue) && !empty($date_post) && !empty($idref_post) && !empty($idRef_auteur)) {
        $reponse = new Reponse($idReponse, $contenue, $date_post, $idref_post, $idRef_auteur);
        $repo = new ReponseRepository($bdd);

        $result = $repo->modifier($reponse);

        if ($result) {
            header('Location: ../../vue/ListeReponse.php');
            exit();
        } else {
            echo "Erreur lors de la modification.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

