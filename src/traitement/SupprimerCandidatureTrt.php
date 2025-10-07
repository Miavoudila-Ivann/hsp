<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/CandidatureRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_candidature = $_GET['id'];
    $repo = new CandidatureRepository($bdd);

    $result = $repo->supprimer($id_candidature);

    if ($result) {
        header('Location: ../../vue/ListeCandidature.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de la candidature.";
    }
}
