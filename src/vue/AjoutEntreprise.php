<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EntrepriseRepository.php';
require_once '../../src/modele/Entreprise.php';

use repository\EvenementRepository;
use modele\Evenement;

$message = '';
$error = '';

try {
    // Connexion à la base
    $database = new Bdd();
    $bdd = $database->getBdd(); // doit retourner un objet PDO
    $repo = new \repository\EntrepriseRepository($bdd);

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_entreprise = trim($_POST['nom_entreprise'] ?? '');
        $rue_entreprise = trim($_POST['rue_entreprise'] ?? '');
        $ville_entreprise = trim($_POST['ville_entreprise'] ?? '');
        $cd_entreprise = trim($_POST['cd_entreprise'] ?? '');
        $site_web = trim($_POST['site_web'] ?? '');

        if ($nom_entreprise && $rue_entreprise && $ville_entreprise && $cd_entreprise && $site_web) {
            $entreprise = new \modele\Entreprise([
                    'nom_entreprise' => $nom_entreprise,
                    'rue_entreprise' => $rue_entreprise,
                    'ville_entreprise' => $ville_entreprise,
                    'cd_entreprise' => $cd_entreprise,
                    'site_web' => $site_web,
            ]);

            if ($repo->ajouter($entreprise)) {
                $message = 'Entrprise ajouté avec succès ! Vous allez être redirigé.';
                echo '<script>setTimeout(function(){ window.location.href = "ListeEntreprise.php"; }, 2000);</script>';
            } else {
                $error = "Erreur lors de l'ajout. Veuillez réessayer.";
            }
        } else {
            $error = 'Tous les champs sont obligatoires.';
        }
    }

} catch (Exception $e) {
    error_log('Erreur : ' . $e->getMessage());
    $error = "Erreur de connexion à la base de données.";
}
?>
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
    <form action="AjoutEntreprise.php" method="POST">
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
