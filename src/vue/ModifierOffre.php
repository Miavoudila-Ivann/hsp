<?php
session_start();

// Vérifier que l'utilisateur est une entreprise
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entreprise') {
    header('Location: ../../index.php');
    exit();
}

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';
require_once '../../src/repository/EntrepriseRepository.php';
require_once '../../src/modele/Offre.php';

use repository\OffreRepository;
use repository\EntrepriseRepository;
use modele\Offre;

$database = new Bdd();
$bdd = $database->getBdd();

$repoOffre = new OffreRepository($bdd);
$repoEntreprise = new EntrepriseRepository($bdd);

// Récupérer l'ID de l'offre
$id_offre = $_GET['id'] ?? null;

if (!$id_offre) {
    die("ID de l'offre manquant.");
}

// ✅ Récupérer l'offre directement avec SQL
$stmt = $bdd->prepare("SELECT * FROM offre WHERE id_offre = ?");
$stmt->execute([$id_offre]);
$offreData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offreData) {
    die("Offre introuvable.");
}

$offre = new Offre($offreData);

// ✅ Vérifier que l'offre appartient bien à cette entreprise
if ($offre->getRefEntreprise() != $_SESSION['id_entreprise']) {
    die("Vous n'êtes pas autorisé à modifier cette offre.");
}

$entreprise = $repoEntreprise->findById($_SESSION['id_entreprise']);

$message = '';
$error = '';

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_offre'])) {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $salaire = trim($_POST['salaire'] ?? '');
    $type_offre = trim($_POST['type_offre'] ?? '');
    $etat = trim($_POST['etat'] ?? 'activer');

    if (empty($titre) || empty($description) || empty($mission) || empty($salaire) || empty($type_offre)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!is_numeric($salaire) || $salaire <= 0) {
        $error = "Le salaire doit être un nombre positif.";
    } else {
        try {
            $offre->setTitre($titre);
            $offre->setDescription($description);
            $offre->setMission($mission);
            $offre->setSalaire((float)$salaire);
            $offre->setTypeOffre($type_offre);
            $offre->setEtat($etat);

            if ($repoOffre->modifier($offre)) {
                $message = "✅ Offre modifiée avec succès !";
            } else {
                $error = "❌ Erreur lors de la modification.";
            }
        } catch (Exception $e) {
            $error = "❌ Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'offre - <?= htmlspecialchars($entreprise->getNom()) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <h1 class="text-xl font-bold text-gray-900">
                    🏢 <?= htmlspecialchars($entreprise->getNom()) ?>
                </h1>
            </div>
            <div class="flex items-center space-x-4">
                <a href="AjouterOffre.php" class="text-blue-600 hover:text-blue-800">← Retour aux offres</a>
                <a href="../../index.php" class="text-blue-600 hover:text-blue-800">Accueil</a>
                <a href="Deconnexion.php" class="text-red-600 hover:text-red-800">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Messages -->
    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de modification -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">✏️ Modifier l'offre</h2>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de l'offre *
                    </label>
                    <input type="text" id="titre" name="titre" required
                           value="<?= htmlspecialchars($offre->getTitre()) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="type_offre" class="block text-sm font-medium text-gray-700 mb-2">
                        Type d'offre *
                    </label>
                    <select id="type_offre" name="type_offre" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="CDI" <?= $offre->getTypeOffre() === 'CDI' ? 'selected' : '' ?>>CDI</option>
                        <option value="CDD" <?= $offre->getTypeOffre() === 'CDD' ? 'selected' : '' ?>>CDD</option>
                        <option value="Stage" <?= $offre->getTypeOffre() === 'Stage' ? 'selected' : '' ?>>Stage</option>
                        <option value="Alternance" <?= $offre->getTypeOffre() === 'Alternance' ? 'selected' : '' ?>>Alternance</option>
                        <option value="Freelance" <?= $offre->getTypeOffre() === 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="salaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Salaire annuel brut (€) *
                    </label>
                    <input type="number" id="salaire" name="salaire" required min="0" step="0.01"
                           value="<?= htmlspecialchars($offre->getSalaire()) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="etat" class="block text-sm font-medium text-gray-700 mb-2">
                        État de l'offre *
                    </label>
                    <select id="etat" name="etat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="activer" <?= $offre->getEtat() === 'activer' ? 'selected' : '' ?>>✅ Active</option>
                        <option value="desactiver" <?= $offre->getEtat() === 'desactiver' ? 'selected' : '' ?>>⏸️ Désactivée</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description du poste *
                </label>
                <textarea id="description" name="description" required rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($offre->getDescription()) ?></textarea>
            </div>

            <div>
                <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">
                    Missions principales *
                </label>
                <textarea id="mission" name="mission" required rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($offre->getMission()) ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="AjouterOffre.php"
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    ← Annuler
                </a>
                <button type="submit" name="modifier_offre"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-200">
                    💾 Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>