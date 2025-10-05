<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Etablissement.php';
require_once '../../src/repository/EtablissementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_etablissement) && !empty($nom_etablissement) && !empty($adresse_etablissement) && !empty($site_web_etablissement)) {
        $etablissement = new Medecin($id_etablissement, $nom_etablissement, $adresse_etablissement, $site_web_etablissement);
        $repo = new EtablissementRepository($bdd);

        $result = $repo->modifier($etablissement);

        if ($result) {
            header('Location: ../vue/ListeEtablissements.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'établissement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>