<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Contact.php';
require_once '../../src/repository/ContactRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_contact) && !empty($sujet) && !empty($message) && !empty($date_envoie) && !empty($status)) {
        $contact = new Contact($id_contact, $sujet, $message, $date_envoie, $status);
        $repo = new ContactRepository($bdd);

        $result = $repo->modifier($contact);

        if ($result) {
            header('Location: ../../vue/ListeContact.php');
            exit();
        } else {
            echo "Erreur lors de la modification du contact.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
