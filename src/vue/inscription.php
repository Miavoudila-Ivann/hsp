<?php
require_once __DIR__ . '/../../src/bdd/Bdd.php';
require_once __DIR__ . '/../../src/repository/UtilisateurRepository.php';

session_start();

try {
    $database = new Bdd('localhost', 'hsp', 'root', '');
    $bdd = $database->getBdd();
    $repo = new UtilisateurRepository($bdd);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
            'nom' => trim($_POST["nom"] ?? ''),
            'prenom' => trim($_POST["prenom"] ?? ''),
            'email' => trim($_POST["email"] ?? ''),
            'rue' => trim($_POST["rue"] ?? ''),
            'code_postal' => trim($_POST["code_postal"] ?? ''),
            'ville' => trim($_POST["ville"] ?? ''),
            'password' => trim($_POST["password"] ?? '')
    ];

    if (!$data['nom'] || !$data['prenom'] || !$data['email'] || !$data['rue'] || !$data['code_postal'] || !$data['ville'] || !$data['password']) {
        $error = "Tous les champs sont requis.";
    } else {
        $result = $repo->inscription($data);
        $success = $result['success'];
        $error = $result['error'];
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); width: 350px; }
        input { width: 100%; padding: 10px; margin: 5px 0 15px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .error { color: red; font-size: 14px; }
        .success { color: green; font-size: 14px; }
        .info { font-size: 12px; color: #555; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Inscription</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success">Inscription réussie ! Vous pouvez maintenant vous connecter.</p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Rue :</label>
        <input type="text" name="rue" required>

        <label>Code postal :</label>
        <input type="text" name="code_postal" required>

        <label>Ville :</label>
        <input type="text" name="ville" required>

        <label>Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <div class="info" id="msg">
            Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
        </div>
        <label class="rgpdRegle">
            <input type="checkbox" name="rgpdRegle" required>
            J’accepte la <a href="politique_confidentialite.php" target="_blank">politique de confidentialité</a>
            et le traitement de mes données dans le cadre de la création de mon compte.
        </label>
        <label class="rgpdCondition">
            <input type="checkbox" name="rgpdCondition" required>
            J’accepte la <a href="condition_utilisation.php" target="_blank">Conditions d’Utilisation et de Services</a>
            et le traitement de mes données dans le cadre de la création de mon compte.
        </label>
        <button type="submit">S'inscrire</button>
        <a href="Connexion.php">Se connecter</a>
    </form>
</div>

<script>
    const pwdInput = document.getElementById('password');
    const msg = document.getElementById('msg');

    pwdInput.addEventListener('input', function() {
        const pwd = this.value;
        let text = "";

        if (pwd.length < 8) text += "❌ 8 caractères minimum<br>";
        if (!/[A-Z]/.test(pwd)) text += "❌ 1 majuscule<br>";
        if (!/[a-z]/.test(pwd)) text += "❌ 1 minuscule<br>";
        if (!/[0-9]/.test(pwd)) text += "❌ 1 chiffre<br>";
        if (!/[\W_]/.test(pwd)) text += "❌ 1 caractère spécial<br>";

        msg.innerHTML = text || "✅ Mot de passe sécurisé";
    });
</script>
</body>
</html>
