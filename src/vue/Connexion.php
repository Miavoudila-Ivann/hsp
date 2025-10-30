<?php
session_start();

require_once __DIR__ . '/../bdd/Bdd.php';                // chemin relatif depuis Connexion.php
require_once __DIR__ . '/../repository/UtilisateurRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === "admin" && $password === "password123") {
        $_SESSION["user"] = "admin";
        $_SESSION["role"] = "admin";
        header("Location: dashboard.php");
        exit();
    }

    $utilisateur = $repo->connexion($username, $password);
    if ($utilisateur) {
        $_SESSION["user"] = $utilisateur->getEmail();
        $_SESSION["mdp"] = $utilisateur->getMdp();
        header("Location: ../../index.php");
        exit();
    } else {
        $error = "Identifiants incorrects.";
        exit;

        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .error { color: red; text-align: center; }
        .footer { text-align: center; margin-top: 15px; font-size: 12px; color: #777; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Connexion</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <div class="footer">admin / password123</div>
</div>

</body>
</html>