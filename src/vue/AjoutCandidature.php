<?php
// Activation des erreurs (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import des classes nécessaires
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/CandidatureRepository.php';
require_once '../../src/modele/Candidature.php';

use repository\CandidatureRepository;
use modele\Candidature;

$message = ''; // Pour message de succès
$error = '';   // Pour message d'erreur

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $repo = new CandidatureRepository($bdd);

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_candidat'] ?? '');
        $prenom = trim($_POST['prenom_candidat'] ?? '');
        $siteWeb = trim($_POST['site_web_candidature'] ?? '');

        // Validation de base
        if (!empty($nom) && !empty($adresse) && !empty($siteWeb)) {
            if (!filter_var($siteWeb, FILTER_VALIDATE_URL)) {
                $error = "L'URL du site web est invalide.";
            } else {
                // Création de l'objet Etablissement
                $candidature = new Candidature([
                        'nom_candidat' => $nom,
                        'prenom_candidat' => $adresse,
                        'site_web_candidature' => $siteWeb
                ]);

                // Insertion dans la BDD
                if ($repo->ajoutCandidature($candidature)) {
                    $message = 'Candidature ajoutée avec succès ! Vous allez être redirigé.';
                    echo '<script>setTimeout(function(){ window.location.href = "AjoutCandidature.php"; }, 2000);</script>';
                } else {
                    $error = "Erreur lors de l'ajout. Veuillez réessayer.";
                }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Etablissement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
        }
        .form-section {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="form-section">
    <h1>Ajouter un Etablissement</h1>

    <!-- Affichage des messages -->
    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom du Candidat :</label>
            <input type="text" name="nom_candidature" id="nom" required value="<?= htmlspecialchars($_POST['nom_candidat'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="adresse">Prenom du Candidat :</label>
            <textarea name="prenom_candidature" id="prenom" required><?= htmlspecialchars($_POST['prenom_candidat'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="site">Site Web :</label>
            <input type="text" name="site_web_candidature" id="site" required value="<?= htmlspecialchars($_POST['site_web_candidature'] ?? '') ?>">
        </div>

        <input type="submit" value="Ajouter la candidature">
    </form>

    <a href="AjoutCandidature.php" class="back-link">← Retour à la liste</a>
</div>
</body>
</html>
