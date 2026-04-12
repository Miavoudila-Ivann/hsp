<?php
/**
 * Page de changement de mot de passe via lien de réinitialisation.
 * Vérifie la validité du token (présent dans l'URL et non expiré en base).
 * Accessible à tout utilisateur possédant un token valide (non connecté requis).
 */

require __DIR__ . '/../bdd/Bdd.php';

$token = $_GET['token'] ?? '';

$db = new Bdd();
$pdo = $db->getBdd();

// Vérification du token : doit exister et ne pas être expiré
$sql = $pdo->prepare("SELECT * FROM utilisateur WHERE reset_token=? AND reset_expires > NOW()");
$sql->execute([$token]);
$utilisateur = $sql->fetch();

if (!$utilisateur) {
    die("Lien invalide ou expiré.");
}
include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
</head>
<body>
<h2>Nouveau mot de passe</h2>

<form method="POST" action="../traitement/ChangerMdpTrt.php">
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <label>Nouveau mot de passe :</label><br>
    <input type="password" name="mdp" required><br><br>

    <button type="submit">Changer le mot de passe</button>
</form>

</body>
</html>
