<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];
    $repo = new UtilisateurRepository($bdd);

    $result = $repo->supprimer($id_utilisateur);

    if ($result) {
        header('Location: ../../vue/ListeUtilisateur.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'utilisateur.";
    }
}
