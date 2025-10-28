<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';
require_once '../../src/modele/Evenement.php';

use repository\EvenementRepository;
use modele\Evenement;

$message = '';
$error = '';

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $repo = new EvenementRepository($bdd);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type_evenement = trim($_POST['type_evenement'] ?? '');
        $lieu = trim($_POST['lieu'] ?? '');
        $nb_place = trim($_POST['nb_place'] ?? '');
        $date_evenement = trim($_POST['date_evenement'] ?? '');

        if (!empty($titre) && !empty($description) && !empty($type_evenement) && !empty($lieu) && !empty($nb_place) && !empty($date_evenement)) {
            $evenement = new Evenement([
                    'titre' => $titre,
                    'description' => $description,
                    'type_evenement' => $type_evenement,
                    'lieu' => $lieu,
                    'nb_place' => $nb_place,
                    'date_evenement' => $date_evenement
            ]);

            if ($repo->creerEvenement($evenement)) {
                $message = 'Événement ajouté avec succès ! Vous allez être redirigé.';
                echo '<script>setTimeout(function(){ window.location.href = "CreeEvenement.php"; }, 2000);</script>';
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
    <title>Créer un événement</title>
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
        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
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
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
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
    <h1>Créer un événement</h1>

    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="titre">Titre de l'événement :</label>
            <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="type_evenement">Type d'événement :</label>
            <select name="type_evenement" id="type_evenement" required>
                <option value="">-- Choisissez un type --</option>
                <option value="conférence" <?= (($_POST['type_evenement'] ?? '') === 'conférence') ? 'selected' : '' ?>>Conférence</option>
                <option value="atelier" <?= (($_POST['type_evenement'] ?? '') === 'atelier') ? 'selected' : '' ?>>Atelier</option>
                <option value="séminaire" <?= (($_POST['type_evenement'] ?? '') === 'séminaire') ? 'selected' : '' ?>>Séminaire</option>
                <option value="formation" <?= (($_POST['type_evenement'] ?? '') === 'formation') ? 'selected' : '' ?>>Formation</option>
                <option value="fête" <?= (($_POST['type_evenement'] ?? '') === 'fête') ? 'selected' : '' ?>>Fête</option>

            </select>
        </div>

        <div class="form-group">
            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" required value="<?= htmlspecialchars($_POST['lieu'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="nb_place">Nombre de places :</label>
            <input type="number" id="nb_place" name="nb_place" required min="1" value="<?= htmlspecialchars($_POST['nb_place'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="date_evenement">Date de l'événement :</label>
            <input type="date" id="date_evenement" name="date_evenement" required value="<?= htmlspecialchars($_POST['date_evenement'] ?? '') ?>">
        </div>

        <input type="submit" value="Créer l'événement">
    </form>

    <a href="ListeEvenement.php" class="back-link">← Retour à la liste</a>
</div>

</body>
</html>
<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';
require_once '../../src/modele/Evenement.php';

$message = '';
$error = '';

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $repo = new EvenementRepository($bdd);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type_evenement = trim($_POST['type_evenement'] ?? '');
        $lieu = trim($_POST['lieu'] ?? '');
        $nb_place = trim($_POST['nb_place'] ?? '');
        $date_evenement = trim($_POST['date_evenement'] ?? '');

        if (!empty($titre) && !empty($description) && !empty($type_evenement) && !empty($lieu) && !empty($nb_place) && !empty($date_evenement)) {
            $evenement = new Evenement([
                    'titre' => $titre,
                    'description' => $description,
                    'type_evenement' => $type_evenement,
                    'lieu' => $lieu,
                    'nb_place' => $nb_place,
                    'date_evenement' => $date_evenement
            ]);

            if ($repo->creerEvenement($evenement)) {
                $message = 'Événement ajouté avec succès ! Vous allez être redirigé.';
                echo '<script>setTimeout(function(){ window.location.href = "CreeEvenement.php"; }, 2000);</script>';
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
    <title>Créer un événement</title>
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
        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
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
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
</head>
</html>
