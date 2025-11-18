<?php
// src/vue/demande_mdp.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
</head>
<body>
<h2>Mot de passe oublié</h2>

<form method="POST" action="../traitement/MdpTrt.php">
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>
    <button type="submit">Envoyer le lien de réinitialisation</button>
</form>

</body>
</html>

