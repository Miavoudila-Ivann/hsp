<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Utilisateur.php';
require_once '../../src/repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_utilisateur) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($rue) && !empty($cd) && !empty($ville)) {
        $utilisateur = new Utilisateur($id_utilisateur, $nom, $prenom, $email, $rue, $cd, $ville);
        $repo = new UtilisateurRepository($bdd);

        $result = $repo->modifier($utilisateur);

        if ($result) {
            header('Location: ../../vue/ListeUtilisateur.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'utilisateur.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
