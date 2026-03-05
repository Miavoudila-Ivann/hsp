<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Mon site' ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #f4f4f4; }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        a { color: #007bff; text-decoration: none; }
        .error { color: red; text-align: center; }
        .success { color: green; text-align: center; }
        .actions a { margin-right: 10px; }
    </style>
    <style>
        nav {
            background: #343a40;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }
        nav a:hover { background: #495057; }
        .nav-user { margin-left: auto; color: #adb5bd; font-size: 14px; }
    </style>
</head>
<body>

<nav>
    <a href="../../index.php">Accueil</a>

    <?php if (isset($_SESSION['role'])): ?>
        <?php $role = $_SESSION['role']; ?>

        <?php if ($role === 'admin'): ?>
            <a href="../../src/vue/ListeUtilisateurs.php">Utilisateurs</a>
            <a href="../../src/vue/ListeMedecin.php">Médecins</a>
            <a href="../../src/vue/ListeHopital.php">Hôpitaux</a>
            <a href="../../src/vue/ListeEtablissement.php">Établissements</a>
            <a href="../../src/vue/ListeEvenement.php">Événements</a>
            <a href="../../src/vue/ListeCandidatures.php">Candidatures</a>
        <?php endif; ?>

        <?php if ($role === 'secretaire'): ?>
            <a href="../../src/vue/ListePatients.php">Patients</a>
            <a href="../../src/vue/ListeDossiers.php">Salle d'attente</a>
        <?php endif; ?>

        <?php if ($role === 'medecin'): ?>
            <a href="../../src/vue/SalleAttente.php">Salle d'attente</a>
            <a href="../../src/vue/ListeChambres.php">Chambres</a>
            <a href="../../src/vue/DemanderStock.php">Demander stock</a>
        <?php endif; ?>

        <?php if ($role === 'gestionnaire_stock'): ?>
            <a href="../../src/vue/ListeProduits.php">Produits</a>
            <a href="../../src/vue/ListeFournisseurs.php">Fournisseurs</a>
            <a href="../../src/vue/ListeDemandesStock.php">Demandes</a>
        <?php endif; ?>

        <span class="nav-user">
            <?= htmlspecialchars(($_SESSION['prenom'] ?? '') . ' ' . ($_SESSION['nom'] ?? '')) ?>
        </span>
        <a href="../../src/traitement/Déconnexion.php">Déconnexion</a>
    <?php endif; ?>
</nav>

<div class="container">
