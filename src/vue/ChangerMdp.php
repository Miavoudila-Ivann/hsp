<?php

require __DIR__ . '/../bdd/Bdd.php';

$token = $_GET['token'] ?? '';

$db = new Bdd();
$pdo = $db->getBdd();

$sql = $pdo->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
$sql->execute([$token]);
$user = $sql->fetch();

if (!$user) {
    die("Lien invalide ou expiré.");
}
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
    <input type="password" name="password" required><br><br>

    <button type="submit">Changer le mot de passe</button>
</form>

</body>
</html>
