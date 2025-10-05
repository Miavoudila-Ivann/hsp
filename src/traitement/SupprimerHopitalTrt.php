<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_hopital'])) {
    $id_hopital = $_GET['id_hopital'];

    $repo = new HopitalRepository($bdd);
    $result = $repo->supprimer($id_hopital);

    if ($result) {
        header('Location: ../vue/ListeHopital.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'hopital.";
    }
} else {
    echo "ID hopital manquant.";
}
