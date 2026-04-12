<?php
/**
 * Traitement de suppression d'un contact.
 * Reçoit l'identifiant du contact via GET,
 * délègue la suppression à ContactRepository,
 * puis redirige vers la liste des contacts.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/ContratRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_contact = $_GET['id'];
    $repo = new ContactRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer($id_contact);

    if ($result) {
        // Succès : retour à la liste des contacts
        header('Location: ../../vue/ListeContact.php');
        exit();
    } else {
        echo "Erreur lors de la suppression du contact.";
    }
}
