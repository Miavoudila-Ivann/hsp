<?php
// Activation des erreurs (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import des classes nécessaires
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';
require_once '../../src/modele/Etablissement.php';

use repository\EtablissementRepository;
use modele\Etablissement;

$message = ''; // Pour message de succès
$error = '';   // Pour message d'erreur

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    // On passe l'objet PDO à EtablissementRepository
    $repo = new EtablissementRepository($bdd);

    // Vérifie si l'ID de l'établissement est passé dans l'URL
    if (!isset($_GET['id'])) {
        die('ID de l\'établissement manquant.');
    }

    $id = (int)$_GET['id'];

    // Récupération des données de l'établissement à modifier
    $etablissement = $repo->getEtablissementById($id);

    // Si l'établissement n'existe pas
    if (!$etablissement) {
        die('Établissement introuvable.');
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_etablissement'] ?? $etablissement['nom_etablissement']);
        $adresse = trim($_POST['adresse_etablissement'] ?? $etablissement['adresse_etablissement']);
        $siteWeb = trim($_POST['site_web_etablissement'] ?? $etablissement['site_web_etablissement']);

        // Validation de base
        if (!empty($nom) && !empty($adresse) && !empty($siteWeb)) {
            if (!filter_var($siteWeb, FILTER_VALIDATE_URL)) {
                $error = "L'URL du site web est invalide.";
            } else {
                // Mise à jour de l'établissement
                $etablissement = new Etablissement([
                    'id_etablissement' => $id,
                    'nom_etablissement' => $nom,
                    'adresse_etablissement' => $adresse,
                    'site_web_etablissement' => $siteWeb
                ]);

                // Mise à jour dans la BDD
                if ($repo->modifierEtablissement($etablissement)) {
                    $message = 'Établissement modifié avec succès !';
                } else {
                    $error = "Erreur lors de la modification de l'établissement. Veuillez réessayer.";
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
    <title>Modifier un Etablissement</title>
    <style>
        /* Style similaire à celui de la page de création */
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
    <h1>Modifier l'Établissement</h1>

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
            <label for="nom">Nom de l'établissement :</label>
            <input type="text" name="nom_etablissement" id="nom" required value="<?= htmlspecialchars($etablissement['nom_etablissement']) ?>">
        </div>

        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <textarea name="adresse_etablissement" id="adresse" required><?= htmlspecialchars($etablissement['adresse_etablissement']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="site">Site Web :</label>
            <input type="text" name="site_web_etablissement" id="site" required value="<?= htmlspecialchars($etablissement['site_web_etablissement']) ?>">
        </div>

        <input type="submit" value="Modifier l'établissement">
    </form>

    <a href="CreeEtablissement.php" class="back-link">← Retour à la liste</a>
</div>
</body>
</html>
