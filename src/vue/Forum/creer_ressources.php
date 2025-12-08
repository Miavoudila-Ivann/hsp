<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../src/bdd/Bdd.php';
session_start();

$pdo = getBdd();

// ID utilisateur connecté - adapter selon votre système de session
$auteur_id = $_SESSION['id_utilisateur'] ?? null;

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $categorie = trim($_POST['categorie'] ?? 'autre');

    if ($titre === '' || $contenu === '') {
        $erreur = "Titre et contenu sont obligatoires.";
    } elseif ($auteur_id === null) {
        $erreur = "Vous devez être connecté pour poster un sujet.";
    } else {
        $sql = "INSERT INTO ressources_contenu (titre, contenu, auteur_id, categorie, date_publication)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$titre, $contenu, $auteur_id, $categorie]);

        header('Location: liste_ressources.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Poster un sujet - Forum HSP</title>
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

        .page { max-width:800px; margin:30px auto 40px; padding:0 16px; }

        .card {
            background:#111827; border-radius:16px; padding:20px 22px;
            border:1px solid #1f2937; box-shadow:0 12px 30px rgba(0,0,0,0.7);
        }

        h1 { font-size:24px; margin-bottom:12px; }

        .form-row { margin-bottom:12px; }
        .form-row label {
            display:block; font-size:13px; margin-bottom:4px; color:#9ca3af;
        }
        .input-text, .input-select, .input-textarea {
            width:100%; border-radius:10px; border:1px solid #374151;
            background:#020617; color:#e5e7eb; padding:8px 10px; font-size:14px;
        }
        .input-textarea { min-height:180px; resize:vertical; }

        .input-text:focus, .input-select:focus, .input-textarea:focus {
            outline:none; border-color:#3b82f6; box-shadow:0 0 0 1px rgba(59,130,246,0.6);
        }

        .btn-primary {
            border-radius:999px; border:none; padding:8px 18px;
            font-size:14px; font-weight:600; cursor:pointer;
            background:linear-gradient(90deg,#22c55e,#16a34a); color:#fff;
            box-shadow:0 0 18px rgba(34,197,94,0.7);
        }
        .btn-primary:hover { transform:translateY(-1px); }

        .error {
            background:#7f1d1d; border-radius:10px; padding:10px 12px;
            margin-bottom:12px; font-size:14px; color:#fee2e2;
            border:1px solid #b91c1c;
        }
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
    </div>
</header>

<main class="page">
    <div class="card">
        <h1>Poster un nouveau sujet</h1>

        <?php if ($erreur): ?>
            <div class="error"><?= htmlspecialchars($erreur); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-row">
                <label for="titre">Titre du sujet</label>
                <input type="text" id="titre" name="titre" class="input-text"
                       required value="<?= htmlspecialchars($_POST['titre'] ?? ''); ?>">
            </div>

            <div class="form-row">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" class="input-select">
                    <option value="general">Général</option>
                    <option value="question">Question</option>
                    <option value="tutoriel">Tutoriel</option>
                    <option value="sante">Santé</option>
                    <option value="autre" selected>Autre</option>
                </select>
            </div>

            <div class="form-row">
                <label for="contenu">Contenu</label>
                <textarea id="contenu" name="contenu" class="input-textarea" required><?=
                    htmlspecialchars($_POST['contenu'] ?? '');
                    ?></textarea>
            </div>

            <button type="submit" class="btn-primary">Publier le sujet</button>
        </form>
    </div>
</main>

</body>
</html>