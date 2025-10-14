<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Hopital.php';
require_once '../../src/repository/HopitalRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_hopital) && !empty($adresse_hopital) && !empty($nom) && !empty($ville_hopital)) {
        $hopital = new Hopital($id_hopital, $adresse_hopital, $nom, $ville_hopital);
        $repo = new HopitalRepository($bdd);

        $result = $repo->ajouter($hopital);

        if ($result) {
            header('Location: ../../vue/PageCreeHopital.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'hopital.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
