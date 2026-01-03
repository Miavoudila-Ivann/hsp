<?php
session_start();

// ✅ VÉRIFICATION : L'utilisateur doit être connecté en tant qu'entreprise
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entreprise') {
    header('Location: ../../index.php');
    exit();
}

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/OffreRepository.php';
require_once '../../src/repository/EntrepriseRepository.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/modele/Entreprise.php';

use repository\OffreRepository;
use repository\EntrepriseRepository;
use modele\Offre;

$database = new Bdd();
$bdd = $database->getBdd();

$repoOffre = new OffreRepository($bdd);
$repoEntreprise = new EntrepriseRepository($bdd);

// Récupérer l'ID de l'entreprise connectée
$id_entreprise = $_SESSION['id_entreprise'] ?? null;

if (!$id_entreprise) {
    die("Erreur : Entreprise non identifiée. Veuillez vous reconnecter.");
}

// Récupérer les informations de l'entreprise
$entreprise = $repoEntreprise->findById($id_entreprise);

if (!$entreprise) {
    die("Erreur : Entreprise introuvable.");
}

$message = '';
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publier_offre'])) {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $mission = trim($_POST['mission'] ?? '');
    $salaire = trim($_POST['salaire'] ?? '');
    $type_offre = trim($_POST['type_offre'] ?? '');
    $date_publication = date('Y-m-d');

    // Validation
    if (empty($titre) || empty($description) || empty($mission) || empty($salaire) || empty($type_offre)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!is_numeric($salaire) || $salaire <= 0) {
        $error = "Le salaire doit être un nombre positif.";
    } else {
        try {
            // Créer l'offre
            $offre = new Offre([
                    'id_offre' => null,
                    'titre' => $titre,
                    'description' => $description,
                    'mission' => $mission,
                    'salaire' => (float)$salaire,
                    'type_offre' => $type_offre,
                    'etat' => 'activer',
                    'ref_utilisateur' => $id_entreprise,
                    'ref_entreprise' => $id_entreprise,
                    'date_publication' => $date_publication
            ]);

            if ($repoOffre->ajouter($offre)) {
                $message = "✅ Offre publiée avec succès !";
                $_POST = [];
            } else {
                $error = "❌ Erreur lors de la publication de l'offre.";
            }
        } catch (Exception $e) {
            $error = "❌ Erreur : " . $e->getMessage();
        }
    }
}

// ✅ Récupérer les offres DIRECTEMENT avec une requête SQL
$stmt = $bdd->prepare("SELECT * FROM offre WHERE ref_entreprise = ? ORDER BY date_publication DESC");
$stmt->execute([$id_entreprise]);
$offresData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$offres = [];
foreach ($offresData as $data) {
    $offres[] = new Offre($data);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une offre - <?= htmlspecialchars($entreprise->getNom()) ?></title>
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
                <span class="text-gray-600"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
                <a href="../../index.php" class="text-blue-600 hover:text-blue-800">Accueil</a>
                <a href="Deconnexion.php" class="text-red-600 hover:text-red-800">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Formulaire de publication -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Publier une nouvelle offre</h2>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de l'offre *
                    </label>
                    <input type="text" id="titre" name="titre" required
                           value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ex: Développeur Full Stack">
                </div>

                <div>
                    <label for="type_offre" class="block text-sm font-medium text-gray-700 mb-2">
                        Type d'offre *
                    </label>
                    <select id="type_offre" name="type_offre" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Sélectionnez --</option>
                        <option value="CDI" <?= ($_POST['type_offre'] ?? '') === 'CDI' ? 'selected' : '' ?>>CDI</option>
                        <option value="CDD" <?= ($_POST['type_offre'] ?? '') === 'CDD' ? 'selected' : '' ?>>CDD</option>
                        <option value="Stage" <?= ($_POST['type_offre'] ?? '') === 'Stage' ? 'selected' : '' ?>>Stage</option>
                        <option value="Alternance" <?= ($_POST['type_offre'] ?? '') === 'Alternance' ? 'selected' : '' ?>>Alternance</option>
                        <option value="Freelance" <?= ($_POST['type_offre'] ?? '') === 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="salaire" class="block text-sm font-medium text-gray-700 mb-2">
                    Salaire annuel brut (€) *
                </label>
                <input type="number" id="salaire" name="salaire" required min="0" step="0.01"
                       value="<?= htmlspecialchars($_POST['salaire'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Ex: 35000">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description du poste *
                </label>
                <textarea id="description" name="description" required rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Décrivez le poste, l'équipe, l'environnement de travail..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">
                    Missions principales *
                </label>
                <textarea id="mission" name="mission" required rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="- Mission 1&#10;- Mission 2&#10;- Mission 3"><?= htmlspecialchars($_POST['mission'] ?? '') ?></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" name="publier_offre"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition duration-200">
                    📤 Publier l'offre
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des offres publiées -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">📋 Mes offres publiées (<?= count($offres) ?>)</h2>

        <?php if (empty($offres)): ?>
            <p class="text-gray-500 text-center py-8">Aucune offre publiée pour le moment.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">État</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($offres as $offre): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($offre->getTitre()) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($offre->getTypeOffre()) ?>
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= number_format($offre->getSalaire(), 0, ',', ' ') ?> €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($offre->getEtat() === 'activer'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Active
                                        </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            ⏸️ Désactivée
                                        </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($offre->getDatePublication())) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="ModifierOffre.php?id=<?= $offre->getIdOffre() ?>"
                                   class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                                <a href="../traitement/SupprimerOffreTrt.php?id=<?= $offre->getIdOffre() ?>"
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>