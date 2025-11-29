<?php
use repository\UtilisateurRepository;

session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
require_once __DIR__ . '/../modele/Utilisateur.php';

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: Connexion.php");
    exit;
}

$hospital_name = htmlspecialchars($_SESSION["hospital_name"] ?? "Hopital Sud Paris");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ TAILWIND OBLIGATOIRE POUR TA NAVBAR -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding-top: 110px; /* ✅ espace pour la navbar */
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            margin: 0 auto 40px; /* ✅ centrage propre */
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .role-info {
            font-size: 14px;
            color: #888;
            margin: 10px 0;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- ✅ NAVBAR CORRIGÉE -->
<nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-600 to-cyan-500 p-2 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-blue-700">
                    <?= $hospital_name ?>
                </span>
            </div>

            <div class="hidden md:flex items-center space-x-8">

                <a href="../../index.php" class="text-gray-700 hover:text-blue-600 font-medium">Accueil</a>
                <a href="ListeEtablissement.php" class="text-gray-700 hover:text-blue-600 font-medium">Etablissements</a>

                <?php if (isset($_SESSION["id_utilisateur"])): ?>
                    <a href="Profil.php" class="text-gray-700 hover:text-blue-600 font-medium">Profil</a>
                <?php endif; ?>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                    <a href="admin.php" class="text-red-600 font-medium">Dashboard Admin</a>
                <?php endif; ?>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "medecin"): ?>
                    <a href="ListeUtilisateurs.php" class="text-red-600 font-medium">Liste élèves</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>
<!-- ✅ FIN NAVBAR -->


<div class="container">
    <h1>Profil</h1>

    <form action="../traitement/ModifierProfilTrt.php" method="POST">

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($_SESSION['nom']) ?>" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($_SESSION['prenom']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>

        <label>Nouveau mot de passe :</label>
        <input type="password" name="mdp" placeholder="Laisser vide si inchangé">

        <div class="role-info">
            <strong>ID :</strong> <?= $_SESSION['id_utilisateur'] ?><br>
            <strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['role']) ?>
        </div>

        <input type="submit" name="ok" value="Mettre à jour">
    </form>

    <div class="form-footer">
        <a href="../vue/Deconnexion.php">Déconnexion</a><br>
        <a href="../../index.php">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>
