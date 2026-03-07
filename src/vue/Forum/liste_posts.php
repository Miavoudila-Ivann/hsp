<?php
session_start();
require_once __DIR__ . '/../../bdd/Bdd.php';

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../Connexion.php');
    exit;
}

$role      = $_SESSION['role'];
$forumType = $_GET['forum'] ?? '';

$acces = [
    'user'       => ['utilisateur'],
    'medecin'    => ['utilisateur', 'medecin'],
    'entreprise' => ['entreprise'],
    'admin'      => ['utilisateur', 'medecin', 'entreprise', 'admin'],
];
if (!in_array($forumType, $acces[$role] ?? [])) {
    header('Location: index.php');
    exit;
}

$labels = [
    'utilisateur' => ['label' => 'Forum Utilisateurs',  'icon' => '👥'],
    'medecin'     => ['label' => 'Forum Médecins',       'icon' => '🩺'],
    'entreprise'  => ['label' => 'Forum Entreprises',    'icon' => '🏢'],
    'admin'       => ['label' => 'Forum Administration', 'icon' => '🔒'],
];
$forumInfo = $labels[$forumType];

// Les médecins ne peuvent pas créer de post dans le forum utilisateur, seulement répondre
$peutPoster = !($role === 'medecin' && $forumType === 'utilisateur');

$bdd = (new Bdd())->getBdd();

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $peutPoster) {
    $titre   = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    if ($titre === '' || $contenu === '') {
        $erreur = 'Le titre et le contenu sont obligatoires.';
    } else {
        $ins = $bdd->prepare("INSERT INTO forum_post (forum_type, titre, contenu, auteur_id) VALUES (?, ?, ?, ?)");
        $ins->execute([$forumType, $titre, $contenu, $_SESSION['id_utilisateur']]);
        header('Location: liste_posts.php?forum=' . urlencode($forumType) . '&ok=1');
        exit;
    }
}

if (isset($_GET['ok'])) $succes = 'Votre post a été publié.';

$stmt = $bdd->prepare("
    SELECT fp.id, fp.titre, fp.contenu, fp.date_creation,
           u.prenom, u.nom,
           (SELECT COUNT(*) FROM forum_reponse fr WHERE fr.post_id = fp.id) AS nb_reponses
    FROM forum_post fp
    LEFT JOIN utilisateur u ON u.id_utilisateur = fp.auteur_id
    WHERE fp.forum_type = ?
    ORDER BY fp.date_creation DESC
");
$stmt->execute([$forumType]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($forumInfo['label']) ?> – HSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm">← Forums</a>
            <span class="text-gray-300">|</span>
            <span class="font-semibold text-gray-800"><?= $forumInfo['icon'] ?> <?= htmlspecialchars($forumInfo['label']) ?></span>
        </div>
        <span class="text-sm text-gray-500"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
    </div>
</nav>

<main class="max-w-4xl mx-auto px-4 py-8">

    <?php if ($succes): ?>
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <?php if ($peutPoster): ?>
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Nouveau post</h2>
        <?php if ($erreur): ?>
            <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-600 rounded text-sm"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                <input type="text" name="titre" required maxlength="200"
                       value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Titre de votre post...">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
                <textarea name="contenu" required rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                          placeholder="Votre message..."><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
            </div>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition-colors">
                Publier
            </button>
        </form>
    </div>
    <?php elseif ($role === 'medecin' && $forumType === 'utilisateur'): ?>
        <div class="mb-6 p-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm">
            En tant que médecin, vous pouvez répondre aux questions des utilisateurs.
        </div>
    <?php endif; ?>

    <h2 class="text-xl font-bold text-gray-900 mb-4">Discussions (<?= count($posts) ?>)</h2>

    <?php if (empty($posts)): ?>
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-400">
            Aucun post pour le moment.
        </div>
    <?php else: ?>
        <div class="flex flex-col gap-3">
            <?php foreach ($posts as $p): ?>
                <a href="voir_post.php?id=<?= (int)$p['id'] ?>&forum=<?= urlencode($forumType) ?>"
                   class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-300 transition-all block">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate"><?= htmlspecialchars($p['titre']) ?></h3>
                            <p class="text-sm text-gray-500 mt-1">
                                <?= htmlspecialchars(mb_strimwidth($p['contenu'], 0, 150, '…')) ?>
                            </p>
                        </div>
                        <div class="text-right shrink-0 text-xs text-gray-400">
                            <div><?= htmlspecialchars(date('d/m/Y', strtotime($p['date_creation']))) ?></div>
                            <div class="mt-1"><?= (int)$p['nb_reponses'] ?> rép.</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-400 mt-2">
                        Par <?= htmlspecialchars(trim($p['prenom'] . ' ' . $p['nom']) ?: 'Inconnu') ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
