<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/ContactRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id'])) {
    $id_contact = $_GET['id'];
    $repo = new ContactRepository($bdd);

    $result = $repo->supprimer($id_contact);

    if ($result) {
        header('Location: ../../vue/ListeContact.php');
        exit();
    } else {
        echo "Erreur lors de la suppression du contact.";
    }
}
