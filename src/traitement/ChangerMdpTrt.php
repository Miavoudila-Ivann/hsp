<?php
date_default_timezone_set('Europe/Paris');

require __DIR__ . '/../bdd/Bdd.php';

$db = new Bdd();
$pdo = $db->getBdd();

$token = $_POST['token'] ?? '';
$mdp = $_POST['mdp'] ?? '';

if (empty($token) || empty($mdp)) {
    die("Erreur : données manquantes.");
}

$sql = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token=? AND reset_expires > NOW()");
$sql->execute([$token]);
$utilisateur = $sql->fetch();

if (!$utilisateur) {
    die("Lien invalide ou expiré.");
}

$newMdp = password_hash($mdp, PASSWORD_DEFAULT);

$sql = $pdo->prepare("UPDATE utilisateur 
                      SET mdp=?, reset_token=NULL, reset_expires=NULL 
                      WHERE reset_token=?");
$sql->execute([$newMdp, $token]);

echo "Mot de passe mis à jour avec succès.";
