<?php
require_once '../../src/bdd/Bdd.php';
global $pdo;
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'hsp';
$user = 'root';
$pass = '';

try {
    $database = new Bdd($host, $dbname, $user, $pass);
    $pdo = $database->getBdd();  // Connexion PDO
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$error = "";
$success = false;

// Fonction de validation du mot de passe
function verifierMotDePasse($password) {
    if (strlen($password) < 8) return "Le mot de passe doit contenir au moins 8 caractères.";
    if (!preg_match('/[A-Z]/', $password)) return "Le mot de passe doit contenir au moins une majuscule.";
    if (!preg_match('/[a-z]/', $password)) return "Le mot de passe doit contenir au moins une minuscule.";
    if (!preg_match('/[0-9]/', $password)) return "Le mot de passe doit contenir au moins un chiffre.";
    if (!preg_match('/[\W_]/', $password)) return "Le mot de passe doit contenir au moins un caractère spécial.";
    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = isset($_POST["nom"]) ? trim($_POST["nom"]) : '';
    $prenom = isset($_POST["prenom"]) ? trim($_POST["prenom"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $rue = isset($_POST["rue"]) ? trim($_POST["rue"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $cp = isset($_POST["code_postal"]) ? trim($_POST["code_postal"]) : '';
    $ville = isset($_POST["ville"]) ? trim($_POST["ville"]) : '';

    if (!$email || !$password || !$nom || !$prenom || !$cp || !$ville) {
        $error = "Tous les champs sont requis.";
    } else {
        // Vérification de la complexité du mot de passe
        $checkPassword = verifierMotDePasse($password);
        if ($checkPassword !== true) {
            $error = $checkPassword;
        } else {
            // Vérifie si l’email existe déjà
            $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
            } else {
                // Insère le nouvel utilisateur avec mot de passe hashé
                $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, prenom, email, rue, cd, ville, mdp) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if ($stmt->execute([$nom, $prenom, $email, $rue, $cp, $ville, $hashedPassword])) {
                    $success = true;
                } else {
                    $error = "Erreur lors de l'inscription.";
                }
            }
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
