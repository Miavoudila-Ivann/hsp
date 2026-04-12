<?php
/**
 * Traite le formulaire de création d'un compte utilisateur.
 * Valide les champs obligatoires (nom, prénom, email, adresse complète)
 * et insère l'utilisateur en base sans mot de passe initial.
 * Redirige vers la liste des utilisateurs après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Utilisateur.php';
require_once '../../src/repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($rue) && !empty($cd) && !empty($ville)) {
        // Le mot de passe et le rôle sont à null : ils seront définis ultérieurement
        $utilisateur = new Utilisateur(null, $prenom, $nom, $email, null, null, $rue, $cd, $ville);
        $repo = new UtilisateurRepository($bdd);

        // Insertion en base de données
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
