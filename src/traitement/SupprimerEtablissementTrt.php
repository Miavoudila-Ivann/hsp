<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_etablissement'])) {
    $id_etablissement = $_GET['id_etablissement'];

    $repo = new EtablissementRepository($bdd);
    $result = $repo->supprimer(id_etablissement);

    if ($result) {
        header('Location: ../../vue/ListeEtablissement.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'établissement.";
    }
} else {
    echo "ID établissement manquant.";
}
?>