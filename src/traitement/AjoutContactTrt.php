<?php
/**
 * Traite le formulaire d'ajout d'un message de contact.
 * Valide les champs obligatoires (sujet, message, date, statut) et insère le contact en base.
 * Redirige vers la liste des contacts après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Contrat.php';
require_once '../../src/repository/ContratRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($id_contact) && !empty($sujet) && !empty($message) && !empty($date_envoie) && !empty($status)) {
        $contact = new Contact($id_contact, $sujet, $message, $date_envoie, $status);
        $repo = new ContactRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($contact);

        if ($result) {
            header('Location: ../../vue/ListeContact.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout du contact.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
