<?php
/**
 * Traitement de suppression d'une spécialité médicale.
 * Reçoit l'identifiant de la spécialité via GET,
 * délègue la suppression à SpecialiteRepository,
 * puis redirige vers la liste des spécialités.
 * Toute requête sans ID est aussi redirigée.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/SpecialiteRepository.php';

if (isset($_GET['id_specialite'])) {
    $id = $_GET['id_specialite'];
    $db = new Bdd();
    $repo = new SpecialiteRepository($db->getBdd());

    // Suppression en base de données
    if ($repo->supprimer($id)) {
        header('Location: ../../vue/ListeSpecialite.php');
        exit();
    } else {
        die("Erreur lors de la suppression.");
    }
} else {
    // Accès sans ID : redirection
    header('Location: ../../vue/ListeSpecialite.php');
    exit();
}
?>