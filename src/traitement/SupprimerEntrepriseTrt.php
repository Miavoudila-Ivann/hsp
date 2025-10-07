<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EntrepriseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_entreprise = $_GET['id'];
    $repo = new EntrepriseRepository($bdd);

    $result = $repo->supprimer($id_entreprise);

    if ($result) {
        header('Location: ../../vue/ListeEntreprise.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'entreprise.";
    }
}
