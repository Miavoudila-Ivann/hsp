<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Utilisateur.php';
require_once '../../src/repository/UtilisateurRepository.php';

// Vérifie que l’utilisateur est admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

if (isset($_POST['ok'])) {
    // On récupère et sécurise les champs
    $id_utilisateur = $_POST['id_utilisateur'] ?? null;
    $prenom         = trim($_POST['prenom'] ?? '');
    $nom            = trim($_POST['nom'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $role           = trim($_POST['role'] ?? '');
    $rue            = trim($_POST['rue'] ?? '');
    $cd             = trim($_POST['cd'] ?? '');
    $ville          = trim($_POST['ville'] ?? '');

    // Vérifie que les champs obligatoires sont remplis
    if (!empty($id_utilisateur) && !empty($prenom) && !empty($nom) && !empty($email) && !empty($role)) {
        $utilisateur = new Utilisateur(
            $id_utilisateur,
            $prenom,
            $nom,
            $email,
            null, // mot de passe non modifié ici
            $role,
            $rue,
            $cd,
            $ville
        );

        $result = $repo->modifierUtilisateur($utilisateur);

        if ($result) {
            // Redirige vers la liste des utilisateurs
            header('Location: ListeUtilisateurs.php');
            exit();
        } else {
            echo "<p style='color:red;'>Erreur lors de la modification de l’utilisateur.</p>";
        }
    } else {
        echo "<p style='color:red;'>Tous les champs obligatoires doivent être remplis.</p>";
    }
} else {
    echo "<p style='color:red;'>Aucune donnée reçue.</p>";
}
?>
