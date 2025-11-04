<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/MedecinRepository.php';

if (isset($_GET['id_medecin'])) {
    $id = $_GET['id_medecin'];
    $db = new Bdd();
    $repo = new MedecinRepository($db->getBdd());

    if ($repo->supprimer($id)) {
        header('Location: ../../vue/ListeMedecin.php');
        exit();
    } else {
        die("Erreur lors de la suppression.");
    }
} else {
    header('Location: ../../vue/ListeMedecin.php');
    exit();
}
?>