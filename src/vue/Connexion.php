<?php

session_start();

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
use repository\UtilisateurRepository;
$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ Utilise l’opérateur ?? pour éviter les warnings
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Vérifie que les deux champs sont remplis
    if ($email !== "" && $password !== "") {

        $utilisateur = $repo->connexion($email, $password);

        if ($utilisateur) {
            $_SESSION["user"] = $utilisateur->getEmail();
            $_SESSION["role"] = $utilisateur->getRole();
            $_SESSION["id_utilisateur"] = $utilisateur->getIdUtilisateur();
            $_SESSION["prenom"] = $utilisateur->getPrenom();
            $_SESSION["nom"] = $utilisateur->getNom();
            $_SESSION["role"] = $utilisateur->getRole();

            header("Location: ../../index.php");
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Veuillez saisir votre email et votre mot de passe.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 { text-align: center; color: #333; }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .error {
            color: red;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Connexion</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>

</div>

</body>
</html>
