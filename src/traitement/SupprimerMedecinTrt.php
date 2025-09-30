<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/MedecinRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_medecin'])) {
    $id_medecin = $_GET['id_medecin'];

    $repo = new MedecinRepository($bdd);
    $result = $repo->supprimer($id_medecin);

    if ($result) {
        header('Location: ../../vue/ListeMedecin.php');
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID médecin manquant.";
}
?>
