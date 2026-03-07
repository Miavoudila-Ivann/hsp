<?php
session_start();
require_once __DIR__ . '/../../bdd/Bdd.php';

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../Connexion.php');
    exit;
}

$role      = $_SESSION['role'];
$postId    = (int)($_GET['id'] ?? 0);
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

$bdd = (new Bdd())->getBdd();

// Récupérer le post
$stmt = $bdd->prepare("
    SELECT fp.*, u.prenom, u.nom, u.role AS auteur_role
    FROM forum_post fp
    LEFT JOIN utilisateur u ON u.id_utilisateur = fp.auteur_id
    WHERE fp.id = ? AND fp.forum_type = ?
");
$stmt->execute([$postId, $forumType]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: liste_posts.php?forum=' . urlencode($forumType));
    exit;
}

// Récupérer les réponses
$stmtR = $bdd->prepare("
    SELECT fr.*, u.prenom, u.nom, u.role AS auteur_role
    FROM forum_reponse fr
    LEFT JOIN utilisateur u ON u.id_utilisateur = fr.auteur_id
    WHERE fr.post_id = ?
    ORDER BY fr.date_creation ASC
");
$stmtR->execute([$postId]);
$reponses = $stmtR->fetchAll(PDO::FETCH_ASSOC);

// Peut-on répondre ?
// Dans le forum utilisateur : user + medecin + admin peuvent répondre
// Dans les autres forums : seuls les membres du forum + admin
$peutRepondre = true;

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $peutRepondre) {
    $contenu = trim($_POST['contenu'] ?? '');
    if ($contenu === '') {
        $erreur = 'La réponse ne peut pas être vide.';
    } else {
        $ins = $bdd->prepare("INSERT INTO forum_reponse (post_id, contenu, auteur_id) VALUES (?, ?, ?)");
        $ins->execute([$postId, $contenu, $_SESSION['id_utilisateur']]);
        header('Location: voir_post.php?id=' . $postId . '&forum=' . urlencode($forumType) . '&ok=1');
        exit;
    }
}

if (isset($_GET['ok'])) $succes = 'Votre réponse a été publiée.';

$labels = [
    'utilisateur' => ['label' => 'Forum Utilisateurs',  'icon' => '👥'],
    'medecin'     => ['label' => 'Forum Médecins',       'icon' => '🩺'],
    'entreprise'  => ['label' => 'Forum Entreprises',    'icon' => '🏢'],
    'admin'       => ['label' => 'Forum Administration', 'icon' => '🔒'],
];
$forumInfo = $labels[$forumType];

function badgeRole(string $role): string {
    $map = [
        'admin'      => ['bg-red-100 text-red-700',    'Admin'],
        'medecin'    => ['bg-green-100 text-green-700','Médecin'],
        'user'       => ['bg-gray-100 text-gray-600',  'Utilisateur'],
        'entreprise' => ['bg-yellow-100 text-yellow-700','Entreprise'],
    ];
    [$cls, $label] = $map[$role] ?? ['bg-gray-100 text-gray-500', $role];
    return "<span class=\"px-2 py-0.5 rounded-full text-xs font-medium $cls\">$label</span>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['titre']) ?> – HSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="liste_posts.php?forum=<?= urlencode($forumType) ?>" class="text-gray-500 hover:text-gray-700 text-sm">
                ← <?= $forumInfo['icon'] ?> <?= htmlspecialchars($forumInfo['label']) ?>
            </a>
        </div>
        <span class="text-sm text-gray-500"><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
    </div>
</nav>

<main class="max-w-3xl mx-auto px-4 py-8">

    <?php if ($succes): ?>
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <!-- Post original -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-3"><?= htmlspecialchars($post['titre']) ?></h1>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <span>Par <strong><?= htmlspecialchars(trim($post['prenom'] . ' ' . $post['nom']) ?: 'Inconnu') ?></strong></span>
            <?= badgeRole($post['auteur_role'] ?? '') ?>
            <span>— <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($post['date_creation']))) ?></span>
        </div>
        <div class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($post['contenu']) ?></div>
    </div>

    <!-- Réponses -->
    <h2 class="text-lg font-semibold text-gray-800 mb-3">
        <?= count($reponses) ?> réponse<?= count($reponses) != 1 ? 's' : '' ?>
    </h2>

    <?php if (empty($reponses)): ?>
        <div class="text-sm text-gray-400 mb-6">Aucune réponse pour le moment.</div>
    <?php else: ?>
        <div class="flex flex-col gap-3 mb-6">
            <?php foreach ($reponses as $r): ?>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                        <strong><?= htmlspecialchars(trim($r['prenom'] . ' ' . $r['nom']) ?: 'Inconnu') ?></strong>
                        <?= badgeRole($r['auteur_role'] ?? '') ?>
                        <span class="ml-auto text-xs"><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($r['date_creation']))) ?></span>
                    </div>
                    <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($r['contenu']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire réponse -->
    <?php if ($peutRepondre): ?>
    <div class="bg-white border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Votre réponse</h3>
        <?php if ($erreur): ?>
            <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-600 rounded text-sm"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        <form method="post">
            <textarea name="contenu" required rows="4"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 mb-3"
                      placeholder="Écrivez votre réponse..."><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition-colors">
                Répondre
            </button>
        </form>
    </div>
    <?php endif; ?>

</main>

</body>
</html>
