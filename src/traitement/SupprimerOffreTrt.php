<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_offre'])) {
    $id_offre = $_GET['id_offre'];

    $repo = new OffreRepository($bdd);
    $result = $repo->supprimer($id_offre);

    if ($result) {
        header('Location: ../../vue/ListeOffre.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID offre manquant.";
}
?>
