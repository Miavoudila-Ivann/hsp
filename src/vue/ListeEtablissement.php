<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';
require_once '../../src/modele/Etablissement.php';

use repository\EtablissementRepository;

session_start();

$pdo = (new \Bdd())->getBdd();
$repo = new EtablissementRepository($pdo);
$etablissements = $pdo->query('SELECT * FROM etablissement')->fetchAll(PDO::FETCH_ASSOC);

$hospital_name = "Hopital Sud Paris";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Établissements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

<!-- 🚀 NAVBAR IDENTIQUE À INDEX -->
<nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- LOGO + NOM -->
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

            <!-- MENU -->
            <div class="hidden md:flex items-center space-x-8">

                <!-- ⭐ Bouton Accueil ajouté ici -->
                <a href="../../index.php" class="text-gray-700 hover:text-blue-600 transition font-medium">Accueil</a>

                <a href="ListeEtablissement.php" class="text-gray-700 hover:text-blue-600 transition font-medium">Etablissements</a>

                <?php if (isset($_SESSION["id_utilisateur"])): ?>
                    <a href="Profil.php" class="text-gray-700 hover:text-blue-600 font-medium">Profil</a>
                <?php endif; ?>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                    <a href="admin.php" class="text-red-600 font-medium">Dashboard Admin</a>
                <?php endif; ?>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "medecin"): ?>
                    <a href="ListeUtilisateurs.php" class="text-red-600 font-medium">Liste élèves</a>
                <?php endif; ?>

                <?php if (!isset($_SESSION["id_utilisateur"])): ?>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 font-medium flex items-center">
                            Rejoins Nous
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="hidden group-hover:block absolute bg-white shadow-lg border rounded-lg mt-2 w-40">
                            <a href="Connexion.php" class="block px-4 py-2 hover:bg-blue-100">Connexion</a>
                            <a href="Inscription.php" class="block px-4 py-2 hover:bg-blue-100">Inscription</a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>


        </div>
    </div>
</nav>
<!-- 🚀 FIN NAVBAR -->

<!-- ESPACE POUR COMPENSER LA NAVBAR FIXED -->
<div class="h-24"></div>


<!-- 🎯 CONTENU PRINCIPAL -->
<h1 class="text-center text-3xl font-bold mb-6">Liste des Établissements</h1>

<div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
    <table class="w-full border-collapse">
        <thead>
        <tr class="bg-gray-200 text-left">
            <th class="p-3 border">ID</th>
            <th class="p-3 border">Nom de l'établissement</th>
            <th class="p-3 border">Adresse</th>
            <th class="p-3 border">Site Web</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($etablissements as $e): ?>
            <tr class="hover:bg-gray-100">
                <td class="p-3 border"><?= htmlspecialchars($e['id_etablissement']) ?></td>
                <td class="p-3 border"><?= htmlspecialchars($e['nom_etablissement']) ?></td>
                <td class="p-3 border"><?= htmlspecialchars($e['adresse_etablissement']) ?></td>
                <td class="p-3 border">
                    <a href="<?= htmlspecialchars($e['site_web_etablissement']) ?>"
                       class="text-blue-600 hover:underline"
                       target="_blank">
                        Voir le site
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
