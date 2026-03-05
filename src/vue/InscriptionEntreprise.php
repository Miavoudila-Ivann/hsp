<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/EntrepriseRepository.php';
require_once __DIR__ . '/../modele/Entreprise.php';

use repository\EntrepriseRepository;
use modele\Entreprise;

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $database = new Bdd();
        $bdd = $database->getBdd();
        $repo = new EntrepriseRepository($bdd);

        // Validation des données
        $nom = trim($_POST['nom_entreprise'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp = trim($_POST['mdp'] ?? '');
        $rue = trim($_POST['rue_entreprise'] ?? '');
        $ville = trim($_POST['ville_entreprise'] ?? '');
        $cd = trim($_POST['cd_entreprise'] ?? '');
        $siteWeb = trim($_POST['site_web'] ?? '');

        if (!$nom || !$email || !$mdp || !$rue || !$ville || !$cd || !$siteWeb) {
            $error = "Tous les champs sont requis.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide.";
        } else {
            // Vérifier si l'email existe déjà
            $stmt = $bdd->prepare("SELECT COUNT(*) FROM entreprise WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Cet email est déjà utilisé.";
            } else {
                // Hash du mot de passe
                $hashedPassword = password_hash($mdp, PASSWORD_BCRYPT);

                $entreprise = new Entreprise([
                    'nom_entreprise' => $nom,
                    'email' => $email,
                    'mdp' => $hashedPassword,
                    'rue_entreprise' => $rue,
                    'ville_entreprise' => $ville,
                    'cd_entreprise' => $cd,
                    'site_web' => $siteWeb,
                    'status' => 'Attente'
                ]);

                if ($repo->inscrireEntreprise($entreprise)) {
                    $success = true;
                } else {
                    $error = "Erreur lors de l'inscription.";
                }
            }
        }
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Entreprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900">
                Inscription Entreprise
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Créez un compte pour votre entreprise
            </p>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <p>✅ Inscription réussie ! Votre compte est en attente de validation par un administrateur.</p>
                <p class="mt-2"><a href="Connexion.php" class="text-blue-600 underline">Se connecter</a></p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="nom_entreprise" class="block text-sm font-medium text-gray-700">
                        Nom de l'entreprise *
                    </label>
                    <input type="text" id="nom_entreprise" name="nom_entreprise" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email professionnel *
                    </label>
                    <input type="email" id="email" name="email" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="mdp" class="block text-sm font-medium text-gray-700">
                        Mot de passe *
                    </label>
                    <input type="password" id="mdp" name="mdp" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">
                        Au moins 12 caractères avec majuscule, minuscule, chiffre et caractère spécial
                    </p>
                </div>

                <div>
                    <label for="rue_entreprise" class="block text-sm font-medium text-gray-700">
                        Adresse *
                    </label>
                    <input type="text" id="rue_entreprise" name="rue_entreprise" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="cd_entreprise" class="block text-sm font-medium text-gray-700">
                            Code postal *
                        </label>
                        <input type="text" id="cd_entreprise" name="cd_entreprise" required
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="ville_entreprise" class="block text-sm font-medium text-gray-700">
                            Ville *
                        </label>
                        <input type="text" id="ville_entreprise" name="ville_entreprise" required
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700">
                        Site Web *
                    </label>
                    <input type="url" id="site_web" name="site_web" required
                           placeholder="https://www.exemple.com"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="rgpd" name="rgpd" required
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="rgpd" class="ml-2 block text-sm text-gray-900">
                    J'accepte la <a href="politique_confidentialite.php" target="_blank" class="text-blue-600 underline">politique de confidentialité</a>
                </label>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    S'inscrire
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte ?
                    <a href="Connexion.php" class="font-medium text-blue-600 hover:text-blue-500">
                        Se connecter
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

</body>
</html>