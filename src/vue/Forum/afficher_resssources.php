<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../src/bdd/Bdd.php';
session_start();

$pdo = getBdd();

if (!isset($_GET['id'])) {
    die("Aucune ressource spécifiée.");
}

$ressourceId = (int)$_GET['id'];

// Récupérer la ressource
$sqlR = "SELECT r.*, u.prenom, u.nom
         FROM ressources_contenu r
         LEFT JOIN utilisateur u ON u.id_utilisateur = r.auteur_id
         WHERE r.id = ?";
$stmt = $pdo->prepare($sqlR);
$stmt->execute([$ressourceId]);
$ressource = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ressource) {
    die("Ressource introuvable.");
}

// Récupérer les commentaires
$sqlC = "SELECT c.*, u.prenom, u.nom
         FROM commentaires c
         LEFT JOIN utilisateur u ON u.id_utilisateur = c.auteur_id
         WHERE c.ressource_id = ?
         ORDER BY c.date_commentaire DESC";
$stmtC = $pdo->prepare($sqlC);
$stmtC->execute([$ressourceId]);
$commentaires = $stmtC->fetchAll(PDO::FETCH_ASSOC);

// Construire arbre + map par id
$tree = [];
$byId = [];

foreach ($commentaires as $c) {
    $parent = $c['parent_id'] ?? null;
    $tree[$parent][] = $c;
    $byId[$c['id']] = $c;
}

// Limiter à 5 commentaires racine
if (isset($tree[null])) {
    $tree[null] = array_slice($tree[null], 0, 5);
}

function afficherCommentaires(?int $parentId, array $tree, array $byId, int $ressourceId): void
{
    if (!isset($tree[$parentId])) return;

    $children = $tree[$parentId];
    $isReplyLevel = $parentId !== null;
    $maxReplies = 2;
    $total = count($children);
    $visible = $isReplyLevel ? array_slice($children, 0, $maxReplies) : $children;

    foreach ($visible as $com) {
        $isReply = $parentId !== null;
        $cardClass = $isReply ? 'comment-card reply-card' : 'comment-card';

        echo '<div class="'.$cardClass.'">';

        $prenom = $com['prenom'] ?? '';
        $nom = $com['nom'] ?? '';
        $auteur = trim($prenom.' '.$nom);
        if ($auteur === '') $auteur = 'Un utilisateur';

        echo '<div class="comment-header"><span class="comment-author">';
        if (!$isReply) {
            echo htmlspecialchars($auteur).' a commenté :';
        } else {
            $parentAuthor = 'un utilisateur';
            if (isset($byId[$parentId])) {
                $pp = $byId[$parentId];
                $ppPrenom = $pp['prenom'] ?? '';
                $ppNom = $pp['nom'] ?? '';
                $tmp = trim($ppPrenom.' '.$ppNom);
                if ($tmp !== '') $parentAuthor = $tmp;
            }
            echo htmlspecialchars($auteur).' a répondu à '.htmlspecialchars($parentAuthor).' :';
        }
        echo '</span></div>';

        echo '<div class="comment-body">'.nl2br(htmlspecialchars($com['contenu'])).'</div>';

        echo '
            <form method="post" action="poster_commentaire.php" class="reply-form">
                <input type="hidden" name="ressource_id" value="'.$ressourceId.'">
                <input type="hidden" name="parent_id" value="'.$com['id'].'">
                <textarea name="contenu" class="input-textarea small" required placeholder="Répondre..."></textarea>
                <button class="btn btn-reply" type="submit">Répondre</button>
            </form>
        ';

        afficherCommentaires((int)$com['id'], $tree, $byId, $ressourceId);

        echo '</div>';
    }

    if ($isReplyLevel && $total > $maxReplies) {
        $remaining = $total - $maxReplies;
        $pid = (int)$parentId;

        echo '
            <button class="btn btn-show-more" data-parent="'.$pid.'">
                Voir plus de réponses ('.$remaining.')
            </button>
            <div class="extra-replies" id="replies-'.$pid.'" style="display:none;">
        ';

        for ($i = $maxReplies; $i < $total; $i++) {
            $com = $children[$i];

            $prenom = $com['prenom'] ?? '';
            $nom = $com['nom'] ?? '';
            $auteur = trim($prenom.' '.$nom);
            if ($auteur === '') $auteur = 'Un utilisateur';

            echo '<div class="comment-card reply-card extra">';
            echo '<div class="comment-header"><span class="comment-author">'
                .htmlspecialchars($auteur).' a répondu :</span></div>';
            echo '<div class="comment-body">'.nl2br(htmlspecialchars($com['contenu'])).'</div>';

            echo '
                <form method="post" action="poster_commentaire.php" class="reply-form">
                    <input type="hidden" name="ressource_id" value="'.$ressourceId.'">
                    <input type="hidden" name="parent_id" value="'.$com['id'].'">
                    <textarea name="contenu" class="input-textarea small" required placeholder="Répondre..."></textarea>
                    <button class="btn btn-reply" type="submit">Répondre</button>
                </form>
            ';

            afficherCommentaires((int)$com['id'], $tree, $byId, $ressourceId);

            echo '</div>';
        }

        echo '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($ressource['titre']); ?> – Forum HSP</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #050505;
            color: #f9fafb;
        }
        a { color: inherit; text-decoration: none; }

        .top-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: rgba(0,0,0,0.9);
            border-bottom: 1px solid #111827;
            backdrop-filter: blur(10px);
        }
        .nav-left { display:flex; align-items:center; gap:12px; }
        .nav-logo { font-weight:700; font-size:18px; letter-spacing:0.08em; text-transform:uppercase; }
        .nav-right { display:flex; align-items:center; gap:10px; }
        .nav-link {
            padding:8px 14px; border-radius:999px; font-size:14px;
            color:#d1d5db; border:1px solid transparent;
        }
        .nav-link:hover { border-color:#374151; background:#111827; }

        .nav-btn {
            border-radius:999px; border:none; padding:8px 18px;
            font-size:14px; font-weight:600; cursor:pointer;
            background:linear-gradient(90deg,#3b82f6,#8b5cf6); color:#fff;
            box-shadow:0 0 18px rgba(59,130,246,0.7);
            transition:transform .15s, box-shadow .15s;
        }
        .nav-btn:hover { transform:translateY(-1px); box-shadow:0 0 26px rgba(139,92,246,0.9); }

        .page { max-width:960px; margin:30px auto 40px; padding:0 16px; }

        .ressource-card {
            background:#111827; border-radius:16px; padding:20px 22px;
            border:1px solid #1f2937; box-shadow:0 12px 30px rgba(0,0,0,0.7);
            margin-bottom:22px;
        }
        .ressource-title { font-size:24px; margin-bottom:6px; }
        .ressource-meta { font-size:13px; color:#9ca3af; margin-bottom:12px; }
        .ressource-body { font-size:15px; line-height:1.6; color:#e5e7eb; }

        .section-title {
            font-size:14px; text-transform:uppercase; letter-spacing:.14em;
            color:#9ca3af; margin:18px 0 10px;
        }

        .comment-card {
            background:#18181b; border-radius:12px;
            padding:10px 12px; margin-bottom:12px;
            border:1px solid #27272a;
        }
        .reply-card { margin-left:24px; background:#020617; }
        .comment-header { margin-bottom:4px; }
        .comment-author { font-size:13px; font-weight:600; color:#93c5fd; }
        .comment-body { font-size:14px; color:#e5e7eb; margin-bottom:8px; }

        .reply-form { margin-top:4px; }
        .input-textarea {
            width:100%; min-height:80px; border-radius:10px;
            border:1px solid #374151; background:#020617;
            color:#e5e7eb; padding:7px 9px; font-size:14px; resize:vertical;
        }
        .input-textarea.small { min-height:60px; }
        .input-textarea:focus {
            outline:none; border-color:#3b82f6; box-shadow:0 0 0 1px rgba(59,130,246,0.6);
        }

        .btn {
            border-radius:999px; border:none; padding:6px 13px;
            font-size:13px; font-weight:500; cursor:pointer;
        }
        .btn-reply { background:#374151; color:#e5e7eb; }
        .btn-reply:hover { background:#4b5563; }
        .btn-show-more {
            margin:4px 0 6px 24px;
            background:transparent; color:#60a5fa; font-size:12px;
            padding:0; border:none; cursor:pointer;
        }
        .btn-show-more:hover { text-decoration:underline; }

        .modal { position:fixed; inset:0; display:none; align-items:center; justify-content:center; z-index:60; }
        .modal.show { display:flex; }
        .modal-backdrop {
            position:absolute; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px);
        }
        .modal-content {
            position:relative; background:#020617; border-radius:18px;
            padding:20px 22px; max-width:480px; width:100%;
            border:1px solid #1f2937; box-shadow:0 20px 60px rgba(0,0,0,0.9); z-index:70;
        }
        .modal-title { font-size:18px; margin-bottom:10px; }
        .modal-row { margin-bottom:10px; }
        .modal-row label { display:block; font-size:13px; margin-bottom:4px; color:#9ca3af; }
        .modal-textarea {
            width:100%; min-height:120px; border-radius:10px;
            border:1px solid #374151; background:#020617;
            color:#e5e7eb; padding:8px 10px; resize:vertical;
        }
        .modal-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:8px; }
        .btn-secondary { border-radius:999px; border:none; padding:6px 14px; font-size:13px; background:#111827; color:#e5e7eb; cursor:pointer; }
        .btn-secondary:hover { background:#1f2937; }
        .btn-primary { border-radius:999px; border:none; padding:6px 14px; font-size:13px; background:#3b82f6; color:#fff; cursor:pointer; }
        .btn-primary:hover { background:#2563eb; }

    </style>
</head>
<body>

<header class="top-nav">
    <div class="nav-left">
        <a href="liste_ressources.php" class="nav-logo">Forum HSP</a>
    </div>
    <div class="nav-right">
        <a class="nav-link" href="liste_ressources.php">Accueil</a>
        <a class="nav-link" href="creer_ressource.php">Poster un sujet</a>
        <button class="nav-btn" id="openCommentModal">Ajouter un commentaire</button>
    </div>
</header>

<main class="page">
    <section class="ressource-card">
        <h1 class="ressource-title"><?= htmlspecialchars($ressource['titre']); ?></h1>
        <div class="ressource-meta">
            <?php
            $auteur = trim(($ressource['prenom'] ?? '').' '.($ressource['nom'] ?? ''));
            if ($auteur === '') $auteur = 'Auteur inconnu';
            ?>
            Par <?= htmlspecialchars($auteur); ?>
            — le <?= htmlspecialchars(date('d/m/Y H:i', strtotime($ressource['date_publication']))); ?>
        </div>
        <div class="ressource-body">
            <?= nl2br(htmlspecialchars($ressource['contenu'])); ?>
        </div>
    </section>

    <h2 class="section-title">Commentaires</h2>
    <?php
    if (empty($commentaires)) {
        echo "<p>Aucun commentaire pour le moment.</p>";
    } else {
        afficherCommentaires(null, $tree, $byId, $ressourceId);
    }
    ?>
</main>

<div class="modal" id="commentModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <h2 class="modal-title">Ajouter un commentaire</h2>
        <form method="post" action="poster_commentaire.php">
            <input type="hidden" name="ressource_id" value="<?= $ressourceId; ?>">
            <div class="modal-row">
                <label for="commentContent">Votre commentaire</label>
                <textarea name="contenu" id="commentContent" class="modal-textarea"
                          required placeholder="Votre message..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="closeCommentModal">Annuler</button>
                <button type="submit" class="btn-primary">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
    const openBtn = document.getElementById('openCommentModal');
    const closeBtn = document.getElementById('closeCommentModal');
    const modal = document.getElementById('commentModal');
    const backdrop = document.querySelector('.modal-backdrop');

    function openModal() { modal.classList.add('show'); }
    function closeModal() { modal.classList.remove('show'); }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);

    document.querySelectorAll('.btn-show-more').forEach(btn => {
        btn.dataset.originalText = btn.textContent;
        btn.addEventListener('click', () => {
            const parentId = btn.dataset.parent;
            const extra = document.getElementById('replies-' + parentId);
            if (!extra) return;
            const hidden = (extra.style.display === '' || extra.style.display === 'none');
            extra.style.display = hidden ? 'block' : 'none';
            btn.textContent = hidden ? 'Voir moins de réponses' : btn.dataset.originalText;
        });
    });
</script>

</body>
</html>