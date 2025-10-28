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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifie que toutes les clés existent
    $nom = isset($_POST["nom"]) ? trim($_POST["nom"]) : '';
    $prenom = isset($_POST["prenom"]) ? trim($_POST["prenom"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $rue = isset($_POST["rue"]) ? trim($_POST["rue"]) : '';
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
    $cp = isset($_POST["code_postal"]) ? trim($_POST["code_postal"]) : '';
    $ville = isset($_POST["ville"]) ? trim($_POST["ville"]) : '';

    // Vérifie que tous les champs sont remplis
    if (!$email || !$password || !$nom || !$prenom || !$cp || !$ville) {
        $error = "Tous les champs sont requis.";
    } else {
        // Vérifie si l’email existe déjà
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Insère le nouvel utilisateur
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
?>

<!-- Partie HTML pour afficher les messages -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<h2>Formulaire d'inscription</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php elseif ($success): ?>
    <p style="color: green;">Inscription réussie !</p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Rue :</label><br>
    <input type="text" name="rue" required><br><br>

    <label>Code postal :</label><br>
    <input type="text" name="code_postal" required><br><br>

    <label>Ville :</label><br>
    <input type="text" name="ville" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">S'inscrire</button>
    <a href="Connexion.php">Se connecter</a>
</form>
</body>
</html>
