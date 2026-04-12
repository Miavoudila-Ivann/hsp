<?php
/**
 * Finalise la réinitialisation du mot de passe via un lien sécurisé.
 * Vérifie la validité du token (non expiré) transmis par email,
 * hache le nouveau mot de passe et invalide le token après usage.
 */
date_default_timezone_set('Europe/Paris');

require __DIR__ . '/../bdd/Bdd.php';

$db  = new Bdd();
$pdo = $db->getBdd();

$token = $_POST['token'] ?? '';
$mdp   = $_POST['mdp'] ?? '';

// Validation : token et nouveau mot de passe sont obligatoires
if (empty($token) || empty($mdp)) {
    die("Erreur : données manquantes.");
}

// Vérification que le token existe et n'est pas expiré
$sql = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token=? AND reset_expires > NOW()");
$sql->execute([$token]);
$utilisateur = $sql->fetch();

if (!$utilisateur) {
    die("Lien invalide ou expiré.");
}

// Hachage sécurisé du nouveau mot de passe
$newMdp = password_hash($mdp, PASSWORD_DEFAULT);

// Mise à jour du mot de passe et invalidation du token pour éviter une réutilisation
$sql = $pdo->prepare("UPDATE utilisateur
                      SET mdp=?, reset_token=NULL, reset_expires=NULL
                      WHERE reset_token=?");
$sql->execute([$newMdp, $token]);

echo "Mot de passe mis à jour avec succès.";
