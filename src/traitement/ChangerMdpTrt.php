<?php

require __DIR__ . '/../bdd/Bdd.php';

$db = new Bdd();
$pdo = $db->getBdd();

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || empty($password)) {
    die("Erreur : données manquantes.");
}

$sql = $pdo->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
$sql->execute([$token]);
$user = $sql->fetch();

if (!$user) {
    die("Lien invalide ou expiré.");
}

$newPass = password_hash($password, PASSWORD_DEFAULT);

$sql = $pdo->prepare("UPDATE users 
                      SET password=?, reset_token=NULL, reset_expires=NULL 
                      WHERE reset_token=?");
$sql->execute([$newPass, $token]);

echo "Mot de passe mis à jour avec succès.";
