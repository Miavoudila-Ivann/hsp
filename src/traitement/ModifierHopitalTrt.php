<?php
/**
 * Traitement de modification d'un hôpital.
 * Valide les champs POST (nom, adresse, ville), construit l'objet Hopital
 * et délègue la mise à jour à HopitalRepository.
 * Redirige vers la page de création/liste des hôpitaux après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Hopital.php';
require_once '../../src/repository/HopitalRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($id_hopital) && !empty($adresse_hopital) && !empty($nom) && !empty($ville_hopital)) {
        $hopital = new Hopital($id_hopital, $adresse_hopital, $nom, $ville_hopital);
        $repo = new HopitalRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($hopital);

        if ($result) {
            // Succès : retour à la page des hôpitaux
            header('Location: ../vue/CreeHopital.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'hopital.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>