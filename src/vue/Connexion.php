<?php
session_start();

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
require_once __DIR__ . '/../repository/EntrepriseRepository.php';

use repository\UtilisateurRepository;
use repository\EntrepriseRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$userRepo = new UtilisateurRepository($bdd);
$entrepriseRepo = new EntrepriseRepository($bdd);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($email !== "" && $password !== "") {
        // Essayer d'abord la connexion utilisateur
        $utilisateur = $userRepo->connexion($email, $password);

        if ($utilisateur) {
            $_SESSION["email"] = $utilisateur->getEmail();
            $_SESSION["role"] = $utilisateur->getRole();
            $_SESSION["id_utilisateur"] = $utilisateur->getIdUtilisateur();
            $_SESSION["prenom"] = $utilisateur->getPrenom();
            $_SESSION["nom"] = $utilisateur->getNom();
            $_SESSION["type_compte"] = "utilisateur";

            header("Location: ../../index.php");
            exit();
        }

        // Si pas d'utilisateur, essayer connexion entreprise
        $entreprise = $entrepriseRepo->connexionEntreprise($email, $password);

        if (is_array($entreprise) && isset($entreprise['error'])) {
            $error = $entreprise['error'];
        } elseif ($entreprise) {
            $_SESSION["email"] = $entreprise->getEmail();
            $_SESSION["role"] = "entreprise";
            $_SESSION["id_entreprise"] = $entreprise->getId();
            $_SESSION["nom_entreprise"] = $entreprise->getNom();
            $_SESSION["type_compte"] = "entreprise";

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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Connexion
            </h2>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="demande_mdp.php" class="text-sm text-blue-600 hover:text-blue-500">
                    Mot de passe oublié ?
                </a>
            </div>

            <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Se connecter
            </button>

            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas de compte ?
                </p>
                <div class="flex flex-col space-y-2">
                    <a href="Inscription.php" class="text-blue-600 hover:text-blue-500 font-medium">
                        → Inscription Utilisateur
                    </a>
                    <a href="InscriptionEntreprise.php" class="text-green-600 hover:text-green-500 font-medium">
                        → Inscription Entreprise
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>