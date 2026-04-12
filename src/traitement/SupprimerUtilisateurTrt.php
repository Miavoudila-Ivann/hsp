<?php
/**
 * Traitement de suppression d'un utilisateur.
 * Reçoit l'identifiant de l'utilisateur via GET,
 * délègue la suppression à UtilisateurRepository,
 * puis redirige vers la liste des utilisateurs.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];
    $repo = new UtilisateurRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_utilisateur);

    if ($result) {
        // Succès : retour à la liste des utilisateurs
        header('Location: ../../vue/ListeUtilisateur.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'utilisateur.";
    }
}
