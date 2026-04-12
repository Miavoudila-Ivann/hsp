<?php
/**
 * Traitement de modification d'un contact.
 * Reçoit les données du formulaire POST, valide que tous les champs
 * sont renseignés, puis met à jour le contact en base via ContactRepository.
 * Redirige vers la liste des contacts en cas de succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Contrat.php';
require_once '../../src/repository/ContratRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($id_contact) && !empty($sujet) && !empty($message) && !empty($date_envoie) && !empty($status)) {
        $contact = new Contact($id_contact, $sujet, $message, $date_envoie, $status);
        $repo = new ContactRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($contact);

        if ($result) {
            // Succès : retour à la liste des contacts
            header('Location: ../../vue/ListeContact.php');
            exit();
        } else {
            echo "Erreur lors de la modification du contact.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
