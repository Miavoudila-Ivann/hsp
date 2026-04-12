<?php
/**
 * Traitement de modification d’un utilisateur (réservé à l’admin).
 * Valide les champs obligatoires (prénom, nom, email, rôle) et les champs
 * optionnels d’adresse. Le mot de passe n’est pas modifiable ici.
 * Met à jour l’utilisateur en base via UtilisateurRepository.
 * Redirige vers la liste des utilisateurs après succès.
 */
session_start();

require_once ‘../../src/bdd/Bdd.php’;
require_once ‘../../src/modele/Utilisateur.php’;
require_once ‘../../src/repository/UtilisateurRepository.php’;

// Contrôle d’accès : réservé aux administrateurs
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

if (isset($_POST[‘ok’])) {
    // Récupération et nettoyage des champs du formulaire
    $id_utilisateur = $_POST[‘id_utilisateur’] ?? null;
    $prenom         = trim($_POST[‘prenom’] ?? ‘’);
    $nom            = trim($_POST[‘nom’] ?? ‘’);
    $email          = trim($_POST[‘email’] ?? ‘’);
    $role           = trim($_POST[‘role’] ?? ‘’);
    $rue            = trim($_POST[‘rue’] ?? ‘’);
    $cd             = trim($_POST[‘cd’] ?? ‘’);
    $ville          = trim($_POST[‘ville’] ?? ‘’);

    // Vérifie que les champs obligatoires sont remplis
    if (!empty($id_utilisateur) && !empty($prenom) && !empty($nom) && !empty($email) && !empty($role)) {
        $utilisateur = new Utilisateur(
            $id_utilisateur,
            $prenom,
            $nom,
            $email,
            null, // le mot de passe n’est pas modifié dans ce formulaire
            $role,
            $rue,
            $cd,
            $ville
        );

        // Mise à jour en base de données
        $result = $repo->modifierUtilisateur($utilisateur);

        if ($result) {
            // Succès : retour à la liste des utilisateurs
            header(‘Location: ListeUtilisateurs.php’);
            exit();
        } else {
            echo "<p style=’color:red;’>Erreur lors de la modification de l’utilisateur.</p>";
        }
    } else {
        echo "<p style=’color:red;’>Tous les champs obligatoires doivent être remplis.</p>";
    }
} else {
    echo "<p style=’color:red;’>Aucune donnée reçue.</p>";
}
?>
