<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Utilisateur non connecté.";
    exit();
}

require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateur.php';
require_once '../../vendor/autoload.php'; // Nécessaire pour PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Bdd();
$bdd = $database->getBdd();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = !empty($_POST['prenom']) ? $_POST['prenom'] : null;
    $nom = !empty($_POST['nom']) ? $_POST['nom'] : null;
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $mdp = !empty($_POST['mdp']) ? $_POST['mdp'] : null;

    $idUtilisateur = $_SESSION['id_utilisateur'];
    $mdpChange = false; // Détection du changement de mdp

    if ($prenom) $_SESSION['prenom'] = $prenom;
    if ($nom) $_SESSION['nom'] = $nom;
    if ($email) $_SESSION['email'] = $email; // Fix : ton code mettait "user" au lieu d'email

    if ($mdp) {
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $mdpChange = true;
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

        // 🔔 ENVOI D'E-MAIL UNIQUEMENT SI MOT DE PASSE MODIFIÉ
        if ($mdpChange) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eidbraim@gmail.com';
                $mail->Password = 'ycak zbav ifrt muei';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('eidbraim@gmail.com', 'Support HSP');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Modification du mot de passe';
                $mail->Body = "
                    Bonjour <b>{$prenom} {$nom}</b>,<br><br>
                    Votre mot de passe vient d’être modifié depuis votre profil.<br><br>
                    ❗ Si vous n’êtes pas à l’origine de cette action, contactez immédiatement le support.<br><br>
                    Cordialement,<br>Support HSP
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Erreur envoi mail mdp modifié : " . $mail->ErrorInfo);
            }
        }

        header('Location: ../vue/Profil.php');
        exit();
    } else {
        echo "Une erreur est survenue lors de la mise à jour.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
