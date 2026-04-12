<?php
/**
 * Traitement de modification du profil de l’utilisateur connecté.
 * Permet la mise à jour sélective des champs (prénom, nom, email, mot de passe).
 * Le mot de passe est hashé en bcrypt avant stockage.
 * Si le mot de passe est modifié, un e-mail de notification est envoyé via PHPMailer.
 * La requête SQL est construite dynamiquement selon les champs fournis.
 * Redirige vers la page profil après succès.
 */
session_start();

// Vérifie que l’utilisateur est authentifié
if (!isset($_SESSION[‘id_utilisateur’])) {
    echo "Utilisateur non connecté.";
    exit();
}

require_once ‘../bdd/Bdd.php’;
require_once ‘../modele/Utilisateur.php’;
require_once ‘../../vendor/autoload.php’; // Nécessaire pour PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$database = new Bdd();
$bdd = $database->getBdd();

if ($_SERVER[‘REQUEST_METHOD’] === ‘POST’) {
    // Récupération des champs modifiables (null si non fournis)
    $prenom = !empty($_POST[‘prenom’]) ? $_POST[‘prenom’] : null;
    $nom    = !empty($_POST[‘nom’])    ? $_POST[‘nom’]    : null;
    $email  = !empty($_POST[‘email’])  ? $_POST[‘email’]  : null;
    $mdp    = !empty($_POST[‘mdp’])    ? $_POST[‘mdp’]    : null;

    $idUtilisateur = $_SESSION[‘id_utilisateur’];
    $mdpChange = false;

    // Mise à jour de la session avec les nouvelles valeurs
    if ($prenom) $_SESSION[‘prenom’] = $prenom;
    if ($nom)    $_SESSION[‘nom’]    = $nom;
    if ($email)  $_SESSION[‘email’]  = $email;

    // Hashage du mot de passe si fourni
    if ($mdp) {
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $mdpChange = true;
    }

    // Construction dynamique des colonnes à mettre à jour
    $updateFields = [];
    $params = [‘id_utilisateur’ => $idUtilisateur];

    if ($prenom) { $updateFields[] = "prenom = :prenom"; $params[‘prenom’] = $prenom; }
    if ($nom)    { $updateFields[] = "nom = :nom";       $params[‘nom’]    = $nom; }
    if ($email)  { $updateFields[] = "email = :email";   $params[‘email’]  = $email; }
    if ($mdp)    { $updateFields[] = "mdp = :mdp";       $params[‘mdp’]    = $mdp; }

    if (empty($updateFields)) {
        echo "Aucune donnée à mettre à jour.";
        exit();
    }

    // Exécution de la mise à jour en base de données
    $req  = "UPDATE utilisateur SET " . implode(", ", $updateFields) . " WHERE id_utilisateur = :id_utilisateur";
    $stmt = $bdd->prepare($req);

    if ($stmt->execute($params)) {

        // Envoi d’un e-mail d’alerte uniquement si le mot de passe a changé
        if ($mdpChange) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = ‘smtp.gmail.com’;
                $mail->SMTPAuth   = true;
                $mail->Username   = ‘eidbraim@gmail.com’;
                $mail->Password   = ‘ycak zbav ifrt muei’;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom(‘eidbraim@gmail.com’, ‘Support HSP’);
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = ‘Modification du mot de passe’;
                $mail->Body    = "
                    Bonjour <b>{$prenom} {$nom}</b>,<br><br>
                    Votre mot de passe vient d’être modifié depuis votre profil.<br><br>
                    Si vous n’êtes pas à l’origine de cette action, contactez immédiatement le support.<br><br>
                    Cordialement,<br>Support HSP
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Erreur envoi mail mdp modifié : " . $mail->ErrorInfo);
            }
        }

        // Succès : retour à la page de profil
        header(‘Location: ../vue/Profil.php’);
        exit();
    } else {
        echo "Une erreur est survenue lors de la mise à jour.";
    }
} else {
    echo "Formulaire non soumis.";
}
?>
