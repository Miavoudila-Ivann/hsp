<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/SpecialiteRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $repo = new SpecialiteRepository($bdd);
    $result = $repo->supprimer($id);

    if ($result) {
        header('Location: ../../vue/ListeSpecialite.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
}
?>
