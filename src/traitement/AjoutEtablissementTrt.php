<?php
/**
 * Traite le formulaire d'ajout d'un établissement de santé.
 * Valide les champs obligatoires (nom, adresse, site web) et insère l'établissement en base.
 * Redirige vers la page de création d'établissement après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Etablissement.php';
require_once '../../src/repository/EtablissementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($id_etablissement) && !empty($nom_etablissement) && !empty($adresse_etablissement) && !empty($site_web_etablissement)) {
        $etablissement = new Etablissement($id_etablissement, $nom_etablissement, $adresse_etablissement, $site_web_etablissement);
        $repo = new EtablissementRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($etablissement);

        if ($result) {
            header('Location: ../../vue/CreeEtablissement.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'établissement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
