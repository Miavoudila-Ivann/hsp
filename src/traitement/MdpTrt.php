<?php
// src/traitement/traitement_mdp.php

require __DIR__ . '/../bdd/Bdd.php';
require __DIR__ . '/../../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connexion DB
$db = new Bdd();
$pdo = $db->getBdd();

$email = $_POST['email'] ?? '';

if (empty($email)) {
    die("Email manquant.");
}

// Vérifier que l’email existe
$sql = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
$sql->execute([$email]);
$user = $sql->fetch();

if (!$user) {
    die("Aucun compte trouvé pour cet email.");
}

// Génération du token sécurisé
$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

$sql = $pdo->prepare("UPDATE utilisateur SET reset_token=?, reset_expires=? WHERE email=?");
$sql->execute([$token, $expires, $email]);

// Lien vers reset
$resetLink = "http://localhost/hsp/src/vue/ChangerMdp.php?token=$token";

// Envoi mail
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'eidbraim@gmail.com';
    $mail->Password   = 'ycak zbav ifrt muei';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('eidbraim@gmail.com', 'Support HSP');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Réinitialisation du mot de passe';
    $mail->Body = "
        Bonjour,<br><br>
        Pour réinitialiser votre mot de passe, cliquez sur ce lien :<br>
        <a href='$resetLink'>$resetLink</a><br><br>
        Ce lien expire dans 1 heure.
    ";

    $mail->send();
    echo "Un email de réinitialisation a été envoyé.";

} catch (Exception $e) {
    echo "Erreur lors de l'envoi: {$mail->ErrorInfo}";
}
