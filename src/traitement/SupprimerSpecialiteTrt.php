<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/SpecialiteRepository.php';

if (isset($_GET['id_specialite'])) {
    $id = $_GET['id_specialite'];
    $db = new Bdd();
    $repo = new SpecialiteRepository($db->getBdd());

    if ($repo->supprimer($id)) {
        header('Location: ../../vue/ListeSpecialite.php');
        exit();
    } else {
        die("Erreur lors de la suppression.");
    }
} else {
    header('Location: ../../vue/ListeSpecialite.php');
    exit();
}
?>