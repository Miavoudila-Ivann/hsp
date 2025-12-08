<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../src/bdd/Bdd.php';
session_start();

$pdo = getBdd();

// Récupération des ressources (sujets)
$sql = "SELECT r.id, r.titre, r.contenu, r.date_publication, u.prenom, u.nom
        FROM ressources_contenu r
        LEFT JOIN utilisateur u ON u.id_utilisateur = r.auteur_id
        ORDER BY r.date_publication DESC";
$stmt = $pdo->query($sql);
$ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Forum HSP - Ressources</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #050505;
            color: #f9fafb;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

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

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #e5e7eb;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 14px;
            color: #d1d5db;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            border-color: #374151;
            background: #111827;
        }

        .nav-btn {
            border-radius: 999px;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            color: #fff;
            box-shadow: 0 0 18px rgba(59,130,246,0.7);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .nav-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 26px rgba(139,92,246,0.9);
        }

        .page {
            max-width: 960px;
            margin: 30px auto 40px;
            padding: 0 16px;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 12px;
        }

        .subtitle {
            font-size: 14px;
            color: #9ca3af;
            margin-bottom: 20px;
        }

        .ressource-card {
            background: #111827;
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 18px;
            border: 1px solid #1f2937;
            box-shadow: 0 12px 30px rgba(0,0,0,0.7);
        }

        .ressource-title {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .ressource-meta {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        .ressource-extrait {
            font-size: 14px;
            color: #e5e7eb;
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .btn-small {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #374151;
            font-size: 13px;
            cursor: pointer;
            background: #020617;
            color: #e5e7eb;
        }

        .btn-small:hover {
            background: #111827;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 60;
        }

        .modal.show {
            display: flex;
        }

        .modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: #020617;
            border-radius: 18px;
            padding: 20px 22px;
            width: 100%;
            max-width: 480px;
            border: 1px solid #1f2937;
            box-shadow: 0 20px 60px rgba(0,0,0,0.9);
            z-index: 70;
        }

        .modal-title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .modal-row {
            margin-bottom: 10px;
        }

        .modal-row label {
            display: block;
            font-size: 13px;
            margin-bottom: 4px;
            color: #9ca3af;
        }

        .modal-select,
        .modal-textarea {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #374151;
            background: #020617;
            color: #e5e7eb;
            padding: 8px 10px;
            font-size: 14px;
        }

        .modal-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-secondary {
            border-radius: 999px;
            border: none;
            padding: 6px 14px;
            font-size: 13px;
            background: #111827;
            color: #e5e7eb;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #1f2937;
        }

        .btn-primary {
            border-radius: 999px;
            border: none;
            padding: 6px 14px;
            font-size: 13px;
            background: #3b82f6;
            color: white;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>

<header class="top-nav">
    <div class="nav-left">
        <span class="nav-logo">Forum HSP</span>
    </div>
    <div class="nav-right">
        <a class="nav-link" href="liste_ressources.php">Accueil</a>
        <a class="nav-link" href="creer_ressource.php">Poster un sujet</a>
        <button class="nav-btn" id="openCommentModal">Ajouter un commentaire</button>
    </div>
</header>

<main class="page">
    <h1>Toutes les ressources</h1>
    <p class="subtitle">Choisis un sujet pour voir les détails et les commentaires.</p>

    <?php if (empty($ressources)): ?>
        <p>Aucune ressource pour le moment.</p>
    <?php else: ?>
        <?php foreach ($ressources as $r): ?>
            <article class="ressource-card">
                <h2 class="ressource-title"><?= htmlspecialchars($r['titre']); ?></h2>
                <div class="ressource-meta">
                    <?php
                    $auteur = trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? ''));
                    if ($auteur === '') $auteur = "Auteur inconnu";
                    ?>
                    Publié par <?= htmlspecialchars($auteur); ?>
                    — le <?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['date_publication']))); ?>
                </div>
                <p class="ressource-extrait">
                    <?= nl2br(htmlspecialchars(mb_strimwidth($r['contenu'], 0, 300, '…'))); ?>
                </p>
                <a href="afficher_ressource.php?id=<?= (int)$r['id']; ?>" class="btn-small">
                    Voir la ressource & les commentaires
                </a>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<div class="modal" id="commentModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <h2 class="modal-title">Ajouter un commentaire</h2>
        <form method="post" action="poster_commentaire.php">
            <div class="modal-row">
                <label for="ressourceSelect">Ressource</label>
                <select name="ressource_id" id="ressourceSelect" class="modal-select" required>
                    <option value="" disabled selected>Choisir une ressource…</option>
                    <?php foreach ($ressources as $r): ?>
                        <option value="<?= (int)$r['id']; ?>">
                            <?= htmlspecialchars($r['titre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-row">
                <label for="commentContent">Commentaire</label>
                <textarea name="contenu" id="commentContent" class="modal-textarea" required
                          placeholder="Votre message..."></textarea>
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
</script>

</body>
</html>