<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../Connexion.php');
    exit;
}

$role = $_SESSION['role'];

$forums = [];
if ($role === 'admin') {
    $forums = [
        ['type' => 'utilisateur', 'label' => 'Forum Utilisateurs',  'icon' => '👥', 'desc' => 'Questions et discussions des utilisateurs'],
        ['type' => 'medecin',     'label' => 'Forum Médecins',       'icon' => '🩺', 'desc' => 'Échanges entre professionnels de santé'],
        ['type' => 'entreprise',  'label' => 'Forum Entreprises',    'icon' => '🏢', 'desc' => 'Espace dédié aux entreprises partenaires'],
        ['type' => 'admin',       'label' => 'Forum Administration', 'icon' => '🔒', 'desc' => 'Forum interne réservé aux administrateurs'],
    ];
} elseif ($role === 'medecin') {
    $forums = [
        ['type' => 'utilisateur', 'label' => 'Forum Utilisateurs', 'icon' => '👥', 'desc' => 'Répondez aux questions des utilisateurs'],
        ['type' => 'medecin',     'label' => 'Forum Médecins',     'icon' => '🩺', 'desc' => 'Échanges entre professionnels de santé'],
    ];
} elseif ($role === 'user') {
    $forums = [
        ['type' => 'utilisateur', 'label' => 'Forum Utilisateurs', 'icon' => '👥', 'desc' => 'Posez vos questions, obtenez des réponses'],
    ];
} elseif ($role === 'entreprise') {
    $forums = [
        ['type' => 'entreprise', 'label' => 'Forum Entreprises', 'icon' => '🏢', 'desc' => 'Espace dédié aux entreprises partenaires'],
    ];
} else {
    header('Location: ../../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum – HSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="../../../index.php" class="text-gray-500 hover:text-gray-700 text-sm">← Accueil</a>
            <span class="text-gray-300">|</span>
            <span class="font-semibold text-gray-800">Forum HSP</span>
        </div>
        <span class="text-sm text-gray-500">
            <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?>
            <span class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs"><?= htmlspecialchars($role) ?></span>
        </span>
    </div>
</nav>

<main class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Forums</h1>
    <p class="text-gray-500 mb-8">Choisissez un forum pour consulter ou publier des discussions.</p>

    <div class="grid gap-4">
        <?php foreach ($forums as $f): ?>
            <a href="liste_posts.php?forum=<?= urlencode($f['type']) ?>"
               class="flex items-center gap-5 bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-blue-300 transition-all">
                <span class="text-4xl"><?= $f['icon'] ?></span>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($f['label']) ?></h2>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($f['desc']) ?></p>
                </div>
                <span class="ml-auto text-gray-400 text-xl">›</span>
            </a>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>
