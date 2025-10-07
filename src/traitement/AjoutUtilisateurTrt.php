<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Utilisateur.php';
require_once '../../src/repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($rue) && !empty($cd) && !empty($ville)) {
        $utilisateur = new Utilisateur(null, $nom, $prenom, $email, $rue, $cd, $ville);
        $repo = new UtilisateurRepository($bdd);

        $result = $repo->ajouter($utilisateur);

        if ($result) {
            header('Location: ../../vue/ListeUtilisateur.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'utilisateur.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
