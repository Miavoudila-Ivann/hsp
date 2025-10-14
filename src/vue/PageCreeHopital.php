<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Imports
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';
require_once '../../src/modele/Hopital.php';

use repository\HopitalRepository;
use modele\Hopital;

$message = '';
$error = '';

try {
    // Connexion BDD
    $database = new Bdd();
    $bdd = $database->getBdd();

    // Instancier le repository
    $repo = new HopitalRepository($bdd);

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $adresse = trim($_POST['adresse_hopital'] ?? '');
        $ville = trim($_POST['ville_hopital'] ?? '');

        if (!empty($nom) && !empty($adresse) && !empty($ville)) {
            // Créer l’objet Hopital
            $hopital = new Hopital([
                'nom' => $nom,
                'adresse_hopital' => $adresse,
                'ville_hopital' => $ville
            ]);

            // Insertion
            if ($repo->creerHopital($hopital)) {
                $message = "Hôpital ajouté avec succès ! Redirection en cours...";
                echo '<script>setTimeout(function(){ window.location.href = "ListeHopital.php"; }, 2000);</script>';
            } else {
                $error = "Erreur lors de l'ajout. Veuillez réessayer.";
            }
        } else {
            $error = "Tous les champs sont obligatoires.";
        }
    }
} catch (Exception $e) {
    error_log("Erreur : " . $e->getMessage());
    $error = "Erreur de connexion à la base de données.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Hôpital</title>
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
        input[type="text"] {
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
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-section">
    <h1>Ajouter un hôpital</h1>

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
            <label for="nom">Nom de l'hôpital :</label>
            <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="adresse_hopital">Adresse :</label>
            <input type="text" id="adresse_hopital" name="adresse_hopital" required value="<?= htmlspecialchars($_POST['adresse_hopital'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="ville_hopital">Ville :</label>
            <input type="text" id="ville_hopital" name="ville_hopital" required value="<?= htmlspecialchars($_POST['ville_hopital'] ?? '') ?>">
        </div>

        <input type="submit" value="Ajouter l'hôpital">
    </form>

    <a href="../../vue/ListeHopital.php" class="back-link">← Retour à la liste des hôpitaux</a>
</div>

</body>
</html>
