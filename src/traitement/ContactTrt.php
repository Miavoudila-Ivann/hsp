<?php
require_once __DIR__ . '/../repository/ContactRepository.php';

$contactRepo = new ContactRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact = new Contact([
        'nom'     => $_POST['nom'],
        'email'   => $_POST['email'],
        'sujet'   => $_POST['sujet'],
        'message' => $_POST['message']
    ]);

    $contactRepo->creerContact($contact);

    // rediriger vers une page de confirmation
    header('Location: merci_contact.php');
    exit();
}

// récupérer tous les contacts pour la partie admin
$listeContacts = $contactRepo->getTousLesContacts();
