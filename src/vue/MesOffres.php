<?php
session_start();

// Vérification que l'utilisateur est bien une entreprise
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'entreprise') {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/OffreRepository.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../repository/EntrepriseRepository.php';
require_once __DIR__ . '/../modele/Offre.php';

use repository\OffreRepository;
use repository\CandidatureRepository;
use repository\EntrepriseRepository;

$database = new Bdd();
$bdd = $database->getBdd();

$offreRepo = new OffreRepository($bdd);
$candidatureRepo = new CandidatureRepository($bdd);
$entrepriseRepo = new EntrepriseRepository($bdd);

$id_entreprise = $_SESSION['id_entreprise'] ?? null;

if (!$id_entreprise) {
    die("Erreur : Entreprise non identifiée.");
}

$entreprise = $entrepriseRepo->findById($id_entreprise);
$message = '';
$error = '';

// Traitement de l'acceptation/refus de candidature
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accepter_candidature'])) {
        $id_candidature = (int)$_POST['id_candidature'];
        if ($candidatureRepo->modifierStatut($id_candidature, 'acceptée')) {
            $message = "✅ Candidature acceptée avec succès !";
        } else {
            $error = "❌ Erreur lors de l'acceptation de la candidature.";
        }
    } elseif (isset($_POST['refuser_candidature'])) {
        $id_candidature = (int)$_POST['id_candidature'];
        if ($candidatureRepo->modifierStatut($id_candidature, 'refusée')) {
            $message = "✅ Candidature refusée.";
        } else {
            $error = "❌ Erreur lors du refus de la candidature.";
        }
    }
}

// Récupération des offres de l'entreprise avec le nombre de candidatures
$stmt = $bdd->prepare("
    SELECT o.*, 
           COUNT(c.id_candidature) as nb_candidatures
    FROM offre o
    LEFT JOIN candidature c ON o.id_offre = c.ref_offre
    WHERE o.ref_entreprise = ?
    GROUP BY o.id_offre
    ORDER BY o.date_publication DESC
");
$stmt->execute([$id_entreprise]);
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Offres - <?= htmlspecialchars($entreprise->getNom()) ?></title>
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
                <a href="AjouterOffre.php" class="text-blue-600 hover:text-blue-800">Publier une offre</a>
                <a href="../../index.php" class="text-gray-600 hover:text-gray-800">Accueil</a>
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

    <h1 class="text-3xl font-bold text-gray-900 mb-8">📋 Mes Offres d'Emploi</h1>

    <?php if (empty($offres)): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-gray-500 mb-4">Vous n'avez pas encore publié d'offres.</p>
            <a href="AjouterOffre.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg">
                📤 Publier ma première offre
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($offres as $offre): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- En-tête de l'offre -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-1">
                                    <?= htmlspecialchars($offre['titre']) ?>
                                </h2>
                                <div class="flex items-center space-x-4 text-blue-100 text-sm">
                                    <span>📅 <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
                                    <span>💰 <?= number_format($offre['salaire'], 0, ',', ' ') ?> €</span>
                                    <span>📝 <?= htmlspecialchars($offre['type_offre']) ?></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-white text-blue-700 px-4 py-2 rounded-full font-bold">
                                    <?= $offre['nb_candidatures'] ?> candidature(s)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des candidatures -->
                    <div class="p-6">
                        <?php
                        // Récupération des candidatures pour cette offre
                        // DEBUG: Vérification
                        error_log("Recherche candidatures pour offre ID: " . $offre['id_offre']);

                        $stmtCandidatures = $bdd->prepare("
                            SELECT c.*, u.nom, u.prenom, u.email, u.ville
                            FROM candidature c
                            INNER JOIN utilisateur u ON c.ref_utilisateur = u.id_utilisateur
                            WHERE c.ref_offre = :id_offre
                            ORDER BY c.date_candidature DESC
                        ");
                        $stmtCandidatures->execute(['id_offre' => $offre['id_offre']]);
                        $candidatures = $stmtCandidatures->fetchAll(PDO::FETCH_ASSOC);

                        // DEBUG
                        error_log("Nombre de candidatures trouvées: " . count($candidatures));
                        ?>

                        <?php if (empty($candidatures)): ?>
                            <p class="text-gray-500 text-center py-4">
                                Aucune candidature pour le moment.
                            </p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($candidatures as $candidature): ?>
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?>
                                                </h3>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <p>📧 <?= htmlspecialchars($candidature['email']) ?></p>
                                                    <p>📍 <?= htmlspecialchars($candidature['ville']) ?></p>
                                                    <p>📅 Candidature envoyée le <?= date('d/m/Y', strtotime($candidature['date_candidature'])) ?></p>
                                                </div>
                                            </div>

                                            <!-- Badge statut -->
                                            <div>
                                                <?php
                                                $statutClass = [
                                                    'En attente' => 'bg-yellow-100 text-yellow-800',
                                                    'acceptée' => 'bg-green-100 text-green-800',
                                                    'refusée' => 'bg-red-100 text-red-800'
                                                ];
                                                $class = $statutClass[$candidature['statut']] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $class ?>">
                                                    <?= htmlspecialchars($candidature['statut']) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Lettre de motivation -->
                                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-gray-700 font-medium mb-1">💬 Lettre de motivation :</p>
                                            <p class="text-sm text-gray-600">
                                                <?= nl2br(htmlspecialchars($candidature['motivation'])) ?>
                                            </p>
                                        </div>

                                        <!-- CV -->
                                        <?php if (!empty($candidature['cv_path'])): ?>
                                            <div class="mb-3">
                                                <a href="../../<?= htmlspecialchars($candidature['cv_path']) ?>"
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    📄 Télécharger le CV
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Actions (uniquement si en attente) -->
                                        <?php if ($candidature['statut'] === 'En attente'): ?>
                                            <div class="flex space-x-2">
                                                <form method="POST" class="flex-1">
                                                    <input type="hidden" name="id_candidature" value="<?= $candidature['id_candidature'] ?>">
                                                    <button type="submit" name="accepter_candidature"
                                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                                                        ✅ Accepter
                                                    </button>
                                                </form>
                                                <form method="POST" class="flex-1">
                                                    <input type="hidden" name="id_candidature" value="<?= $candidature['id_candidature'] ?>">
                                                    <button type="submit" name="refuser_candidature"
                                                            onclick="return confirm('Voulez-vous vraiment refuser cette candidature ?')"
                                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                                                        ❌ Refuser
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>