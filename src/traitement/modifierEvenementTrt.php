<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Evenement.php';
require_once '../../src/repository/EvenementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_evenement) && !empty($date_evenement) && !empty($description) && !empty($lieu) && !empty($nb_place) && !empty($titre) && !empty($type_evenement)) {
        $evenement = new Evenement($id_evenement, $date_evenement, $description, $lieu, $nb_place, $titre, $type_evenement);
        $repo = new EvenementRepository($bdd);

        $result = $repo->modifier($evenement);

        if ($result) {
            header('Location: ../vue/PageCreeEvenement.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'évenement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>