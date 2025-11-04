<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Utilisateur non connecté.";
    exit();
}

require_once '../../src/bdd/Bdd.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    $prenom = isset($_POST['prenom']) && !empty($_POST['prenom']) ? $_POST['prenom'] : null;
    $nom = isset($_POST['nom']) && !empty($_POST['nom']) ? $_POST['nom'] : null;
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null;
    $mdp = isset($_POST['mdp']) && !empty($_POST['mdp']) ? $_POST['mdp'] : null;


    $idUtilisateur = $_SESSION['user_id'];


    if ($prenom) $_SESSION['prenom'] = $prenom;
    if ($nom) $_SESSION['nom'] = $nom;
    if ($email) $_SESSION['email'] = $email;

    if ($mdp) {
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
    }


    $updateFields = [];
    $params = ['id_utilisateur' => $idUtilisateur];

    if ($prenom) {
        $updateFields[] = "prenom = :prenom";
        $params['prenom'] = $prenom;
    }

    if ($nom) {
        $updateFields[] = "nom = :nom";
        $params['nom'] = $nom;
    }

    if ($email) {
        $updateFields[] = "email = :email";
        $params['email'] = $email;
    }

    if ($mdp) {
        $updateFields[] = "mdp = :mdp";
        $params['mdp'] = $mdp;
    }

    if (empty($updateFields)) {
        echo "Aucune donnée à mettre à jour.";
        exit();
    }


    $req = "UPDATE utilisateur SET " . implode(", ", $updateFields) . " WHERE id_utilisateur = :id_utilisateur";
    // implode tableau en chaine de caractère


    $stmt = $bdd->prepare($req);

    if ($stmt->execute($params)) {
        echo "Mise à jour réussie!";

        header('Location: ../../vue/Profil.php');
        exit();
    } else {
        echo "Une erreur est survenue lors de la mise à jour.";
    }
} else {
    echo "Formulaire non soumis.";
}
