<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Utilisateur non connecté.";
    exit();
}

require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateur.php';


$database = new Bdd();
$bdd = $database->getBdd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = !empty($_POST['prenom']) ? $_POST['prenom'] : null;
    $nom = !empty($_POST['nom']) ? $_POST['nom'] : null;
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $mdp = !empty($_POST['mdp']) ? $_POST['mdp'] : null;

    $idUtilisateur = $_SESSION['id_utilisateur'];

    if ($prenom) $_SESSION['prenom'] = $prenom;
    if ($nom) $_SESSION['nom'] = $nom;
    if ($email) $_SESSION['user'] = $email;

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
    $stmt = $bdd->prepare($req);

    if ($stmt->execute($params)) {
        header('Location: ../vue/Profil.php');
        exit();
    } else {
        echo "Une erreur est survenue lors de la mise à jour.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
