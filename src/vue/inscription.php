<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'hsp';
$user = 'root';
$pass = ''; //

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $code_postal = trim($_POST["code_postal"]);
    $ville = trim($_POST["ville"]);

    if (!$email || !$password || !$nom || !$prenom || !$code_postal || !$ville) {
        $error = "Tous les champs sont requis.";
    } else {
        // Vérifie si l’email est déjà utilisé
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Insère le nouvel utilisateur
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, cd, ville, mdp) VALUES (?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$nom, $prenom, $email, $code_postal, $ville, $hashedPassword]);
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 20px; border-radius: 10px; width: 320px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin-top: 8px; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; margin-top: 15px; background: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        .msg { text-align: center; color: <?= $error ? 'red' : 'green' ?>; margin-bottom: 10px; }
        a { display: block; text-align: center; margin-top: 15px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="box">
    <h2>Inscription</h2>

    <?php if ($success): ?>
        <p class="msg">Inscription réussie !</p>
        <a href="index.php">Se connecter maintenant</a>
    <?php else: ?>
        <?php if ($error): ?><p class="msg"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="code_postal" placeholder="Code postal" required>
            <input type="text" name="ville" placeholder="Ville" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>
        <a href="index.php">Déjà inscrit ? Se connecter</a>
    <?php endif; ?>
</div>

</body>
</html>
