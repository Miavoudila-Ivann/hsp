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
    <nav>
        <a href="#profil">Profil d'Entreprise</a>
        <a href="#offres">Publication d'Offres</a>
        <a href="#evenements">Publication d’Événements</a>
    </nav>
</header>

<!-- ====================== PROFIL ENTREPRISE ======================= -->
<section id="profil" class="card">
    <h2>Créer un Profil d’Entreprise</h2>
    <form action="insert_entreprise.php" method="POST">
        <input type="text" name="nom_entreprise" placeholder="Nom de l'entreprise" required>
        <input type="text" name="rue_entreprise" placeholder="Rue" required>
        <input type="text" name="ville_entreprise" placeholder="Ville" required>
        <input type="number" name="cd_entreprise" placeholder="Code postal" required>
        <input type="url" name="site_web" placeholder="Site web" required>
        <button type="submit">Créer le profil</button>
    </form>
</section>

<!-- ====================== OFFRES ======================= -->
<section id="offres" class="card">
    <h2>Publier une Offre</h2>
    <form action="insert_offre.php" method="POST">
        <input type="text" name="titre" placeholder="Titre de l'offre" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <textarea name="mission" placeholder="Missions principales" required></textarea>
        <input type="number" name="salaire" placeholder="Salaire (en €)" required>
        <select name="type_offre" required>
            <option value="emploi">Emploi</option>
            <option value="stage">Stage</option>
            <option value="projet">Projet</option>
        </select>
        <input type="text" name="etat" placeholder="État (actif, inactif...)" required>
        <input type="text" name="ref_utilisateur" placeholder="Référence utilisateur" required>
        <input type="text" name="ref_entreprise" placeholder="Référence entreprise" required>
        <button type="submit">Publier l'offre</button>
    </form>
</section>

<!-- ====================== EVENEMENTS ======================= -->
<section id="evenements" class="card">
    <h2>Proposer un Événement</h2>
    <form action="insert_evenement.php" method="POST">
        <input type="text" name="titre" placeholder="Titre de l’événement" required>
        <textarea name="description" placeholder="Description de l’événement" required></textarea>
        <input type="text" name="type_evenement" placeholder="Type d’événement" required>
        <input type="text" name="lieu" placeholder="Lieu" required>
        <input type="number" name="nb_place" placeholder="Nombre de places" required>
        <input type="date" name="date_evenement" required>
        <button type="submit">Publier l’événement</button>
    </form>
</section>

<footer>
    <p>&copy; 2025 Espace Entreprises - Collaboration Hôpitaux</p>
</footer>

</body>
</html>
