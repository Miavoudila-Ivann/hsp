<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

if (!isset($_GET['email'])) {
    header("Location: ListeUtilisateurs.php");
    exit();
}

$email = $_GET['email'];
$utilisateur = $repo->getUtilisateurParMail($email);

if (!$utilisateur) {
    header("Location: ListeUtilisateurs.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilisateur->setPrenom($_POST['prenom'] ?? '');
    $utilisateur->setNom($_POST['nom'] ?? '');
    $utilisateur->setEmail($_POST['email'] ?? '');
    $utilisateur->setRue($_POST['rue'] ?? '');
    $utilisateur->setCd($_POST['cd'] ?? 0);
    $utilisateur->setVille($_POST['ville'] ?? '');

    if ($repo->modifierUtilisateur($utilisateur)) {
        $success = "Utilisateur modifié avec succès !";
    } else {
        $error = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .error {
            background-color: #f8d7da;
            color: #842029;
        }
        .success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 12px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>

<h2>Modifier utilisateur</h2>
<a class="back-btn" href="ListeUtilisateurs.php">&larr; Retour à la liste</a>

<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="message success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="post">
    <label>Prénom :
        <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>">
    </label>
    <label>Nom :
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur->getNom()) ?>">
    </label>
    <label>Email :
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur->getEmail()) ?>">
    </label>
    <label>Rue :
        <input type="text" name="rue" value="<?= htmlspecialchars($utilisateur->getRue()) ?>">
    </label>
    <label>Code postal :
        <input type="text" name="cd" value="<?= htmlspecialchars($utilisateur->getCd()) ?>">
    </label>
    <label>Ville :
        <input type="text" name="ville" value="<?= htmlspecialchars($utilisateur->getVille()) ?>">
    </label>
    <button type="submit">Modifier</button>
</form>

</body>
</html>
