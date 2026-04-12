<?php
/**
 * Traitement de suppression d'un médecin.
 * Reçoit l'identifiant du médecin via GET,
 * délègue la suppression à MedecinRepository,
 * puis redirige vers la liste des médecins.
 * Toute requête sans ID est aussi redirigée.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/MedecinRepository.php';

if (isset($_GET['id_medecin'])) {
    $id = $_GET['id_medecin'];
    $db = new Bdd();
    $repo = new MedecinRepository($db->getBdd());

    // Suppression en base de données
    if ($repo->supprimer($id)) {
        header('Location: ../../vue/ListeMedecin.php');
        exit();
    } else {
        die("Erreur lors de la suppression.");
    }
} else {
    // Accès sans ID : redirection
    header('Location: ../../vue/ListeMedecin.php');
    exit();
}
?>