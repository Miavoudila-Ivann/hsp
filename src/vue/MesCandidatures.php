<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';

$database = new Bdd();
$bdd = $database->getBdd();

$userId = $_SESSION['id_utilisateur'];
$hospital_name = htmlspecialchars($_SESSION["hospital_name"] ?? "Hopital Sud Paris");

// Récupérer toutes les candidatures de l'utilisateur avec TOUTES les informations
$stmt = $bdd->prepare("
    SELECT 
        c.id_candidature,
        c.motivation,
        c.statut,
        c.date_candidature,
        c.cv_path,
        c.ref_offre,
        o.titre as titre_offre,
        o.type_offre,
        o.salaire,
        o.ref_entreprise,
        e.nom_entreprise
    FROM candidature c
    LEFT JOIN offre o ON c.ref_offre = o.id_offre
    LEFT JOIN entreprise e ON o.ref_entreprise = e.id_entreprise
    WHERE c.ref_utilisateur = ?
    ORDER BY c.date_candidature DESC
");
$stmt->execute([$userId]);
$candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour obtenir la classe CSS selon le statut
function getStatutClass($statut) {
    switch(strtolower($statut)) {
        case 'en attente':
            return 'bg-yellow-100 text-yellow-800 border-yellow-300';
        case 'acceptée':
            return 'bg-green-100 text-green-800 border-green-300';
        case 'refusée':
            return 'bg-red-100 text-red-800 border-red-300';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-300';
    }
}

// Fonction pour obtenir l'emoji selon le statut
function getStatutEmoji($statut) {
    switch(strtolower($statut)) {
        case 'en attente':
            return '⏳';
        case 'acceptée':
            return '✅';
        case 'refusée':
            return '❌';
        default:
            return '📄';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Candidatures - <?= $hospital_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            padding-top: 80px;
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
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
                <a href="ListeEtablissement.php" class="text-gray-700 hover:text-blue-600 font-medium">Établissements</a>
                <a href="AjoutCandidature.php" class="text-gray-700 hover:text-blue-600 font-medium">Nouvelle Candidature</a>
                <a href="Profil.php" class="text-gray-700 hover:text-blue-600 font-medium">Profil</a>
                <a href="Deconnexion.php" class="text-red-600 hover:text-red-800 font-medium">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>

<!-- CONTENU PRINCIPAL -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Candidatures</h1>
        <p class="text-gray-600">Suivez l'état de vos candidatures et leurs détails</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <?php
        $total = count($candidatures);
        $enAttente = count(array_filter($candidatures, fn($c) => strtolower($c['statut']) === 'en attente'));
        $acceptees = count(array_filter($candidatures, fn($c) => strtolower($c['statut']) === 'acceptée'));
        $refusees = count(array_filter($candidatures, fn($c) => strtolower($c['statut']) === 'refusée'));
        ?>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $total ?></p>
                </div>
                <div class="text-3xl">📊</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600"><?= $enAttente ?></p>
                </div>
                <div class="text-3xl">⏳</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Acceptées</p>
                    <p class="text-2xl font-bold text-green-600"><?= $acceptees ?></p>
                </div>
                <div class="text-3xl">✅</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Refusées</p>
                    <p class="text-2xl font-bold text-red-600"><?= $refusees ?></p>
                </div>
                <div class="text-3xl">❌</div>
            </div>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <?php if (empty($candidatures)): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune candidature</h3>
            <p class="text-gray-600 mb-6">Vous n'avez pas encore envoyé de candidature.</p>
            <a href="AjoutCandidature.php"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Postuler à une offre
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($candidatures as $cand): ?>
                <?php
                $nomEntreprise = $cand['nom_entreprise'] ?? 'Entreprise inconnue';
                ?>

                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <!-- En-tête de la candidature -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        <?= htmlspecialchars($cand['titre_offre'] ?? 'Offre non disponible') ?>
                                    </h3>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border <?= getStatutClass($cand['statut']) ?>">
                                        <?= getStatutEmoji($cand['statut']) ?> <?= htmlspecialchars($cand['statut']) ?>
                                    </span>
                                </div>
                                <p class="text-gray-600 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <?= htmlspecialchars($nomEntreprise) ?>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500">Candidaté le</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?= date('d/m/Y', strtotime($cand['date_candidature'])) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Détails de l'offre -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <?php if (!empty($cand['type_offre'])): ?>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Type</p>
                                        <p class="text-sm font-medium"><?= htmlspecialchars($cand['type_offre']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($cand['salaire'])): ?>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500">Salaire</p>
                                        <p class="text-sm font-medium"><?= number_format($cand['salaire'], 0, ',', ' ') ?> €</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500">CV</p>
                                    <?php if (!empty($cand['cv_path'])): ?>
                                        <a href="../../<?= htmlspecialchars($cand['cv_path']) ?>"
                                           target="_blank"
                                           class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                            Voir le CV
                                        </a>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-400">Non fourni</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Motivation -->
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">📝 Lettre de motivation :</p>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($cand['motivation'])) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Actions selon le statut -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <?php if (strtolower($cand['statut']) === 'acceptée'): ?>
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Félicitations ! Votre candidature a été acceptée</span>
                                </div>
                            <?php elseif (strtolower($cand['statut']) === 'refusée'): ?>
                                <div class="flex items-center gap-2 text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Candidature non retenue</span>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-2 text-yellow-600">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="font-medium">En cours d'examen par l'entreprise</span>
                                </div>
                            <?php endif; ?>

                            <p class="text-xs text-gray-400">
                                ID: #<?= htmlspecialchars($cand['id_candidature']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton pour nouvelle candidature -->
        <div class="mt-8 text-center">
            <a href="AjoutCandidature.php"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Postuler à une nouvelle offre
            </a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>