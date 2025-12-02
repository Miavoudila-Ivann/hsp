<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Entreprises</title>
    <style>
        /* 🎨 === STYLE CSS DIRECTEMENT INTÉGRÉ === */

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background: #004e89;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .card {
            background: white;
            max-width: 700px;
            margin: 30px auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            color: #004e89;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        input, select, textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background: #004e89;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #006bb3;
        }

        footer {
            text-align: center;
            padding: 15px;
            background: #004e89;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <h1>Espace Partenaires - Entreprises</h1>
</header>

<section id="profil" class="card">
    <h2>Créer un Profil d’Entreprise</h2>
    <form action="Entreprise.php" method="POST">
        <input type="text" name="nom_entreprise" placeholder="Nom de l'entreprise" required>
        <input type="text" name="rue_entreprise" placeholder="Rue" required>
        <input type="text" name="ville_entreprise" placeholder="Ville" required>
        <input type="number" name="cd_entreprise" placeholder="Code postal" required>
        <input type="url" name="site_web" placeholder="Site web" required>
        <button type="submit">Créer le profil</button>
    </form>
</section>

<footer>
    <p>&copy; 2025 Espace Entreprises - Collaboration Hôpitaux</p>
</footer>

</body>
</html>
