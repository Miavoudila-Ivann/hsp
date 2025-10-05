<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_evenement'])) {
    $id_evenement = $_GET['id_evenement'];

    $repo = new EvenementRepository($bdd);
    $result = $repo->supprimer($id_evenement);

    if ($result) {
        header('Location: ../../vue/ListeEvenement.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'évenement.";
    }
} else {
    echo "ID évenement manquant.";
}
?>