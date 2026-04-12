<?php
/**
 * Traite le formulaire d'ajout d'un hôpital.
 * Valide les champs obligatoires (nom, adresse, ville) et insère l'hôpital en base.
 * Redirige vers la page de création d'hôpital après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Hopital.php';
require_once '../../src/repository/HopitalRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($id_hopital) && !empty($adresse_hopital) && !empty($nom) && !empty($ville_hopital)) {
        $hopital = new Hopital($id_hopital, $adresse_hopital, $nom, $ville_hopital);
        $repo = new HopitalRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($hopital);

        if ($result) {
            header('Location: ../../vue/CreeHopital.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'hopital.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
