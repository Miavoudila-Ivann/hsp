<?php
// Activation des erreurs pour dev (désactive en prod)
error_reporting(E_ALL);
ini_set('display_errors', 1);

use repository\EtablissementRepository;

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';

$message = ''; // Pour messages de succès/erreur
$error = '';   // Pour erreurs spécifiques

try {
    $database = new Bdd();
    $bdd = $database->getBdd();
    $repo = new EtablissementRepository($bdd);

    // Gestion de l'ajout (seulement si formulaire soumis)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_etablissement'] ?? '');
        $adresse = trim($_POST['adresse_etablissement'] ?? '');
        $siteWeb = trim($_POST['site_web_etablissement'] ?? '');

        // Validation basique
        if (!empty($nom)) {
            if ($repo->add($nom, $adresse, $siteWeb)) {
                $message = 'Établissement ajouté avec succès ! Vous allez être redirigé vers la liste.';
                // Redirection après 2 secondes (ou utilise header() pour immédiat)
                echo '<script>setTimeout(function(){ window.location.href = "ListeEtablissement.php"; }, 2000);</script>';
            } else {
                $error = 'Erreur lors de l\'ajout. Vérifiez les données et réessayez.';
            }
        } else {
            $error = 'Le nom de l\'établissement est obligatoire.';
        }
    }

} catch (Exception $e) {
    error_log('Erreur générale: ' . $e->getMessage());
    $error = 'Erreur lors de la connexion à la base de données. Veuillez réessayer.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Etablissement</title>
    <link rel="stylesheet" href="styles.css">  <!-- Si tu as un fichier CSS, sinon utilise le style inline ci-dessous -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-section {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        input[type="submit"] {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .message {
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
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
    <h1>Ajouter un Nouvel Etablissement</h1>

    <!-- Messages de succès/erreur -->
    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom de l'établissement * :</label>
            <input type="text" id="nom" name="nom_etablissement" required
                   placeholder="Ex: Lycée Victor Hugo"
                   value="<?php echo htmlspecialchars($_POST['nom_etablissement'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <textarea id="adresse" name="adresse_etablissement"
                      placeholder="Ex: 123 Rue de la Paix, 75001 Paris"><?php echo htmlspecialchars($_POST['adresse_etablissement'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="site">Site Web :</label>
            <input type="text" id="site" name="site_web_etablissement"
                   placeholder="Ex: example.com ou https://example.com"
                   value="<?php echo htmlspecialchars($_POST['site_web_etablissement'] ?? ''); ?>">
        </div>

        <input type="submit" value="Ajouter l'établissement">
    </form>

    <a href="ListeEtablissement.php" class="back-link">← Retour à la liste des établissements</a>
</div>
</body>
</html>