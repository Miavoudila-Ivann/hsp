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

    // Vérifie si un ID d'événement est passé dans l'URL
    if (!isset($_GET['id'])) {
        die('ID de l\'événement manquant.');
    }

    $id = (int)$_GET['id'];
    $evenement = $repo->getEvenementById($id);

    // Si l'événement n'existe pas, afficher une erreur
    if (!$evenement) {
        die('Événement introuvable.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? $evenement['titre']);
        $description = trim($_POST['description'] ?? $evenement['description']);
        $type_evenement = trim($_POST['type_evenement'] ?? $evenement['type_evenement']);
        $lieu = trim($_POST['lieu'] ?? $evenement['lieu']);
        $nb_place = trim($_POST['nb_place'] ?? $evenement['nb_place']);
        $date_evenement = trim($_POST['date_evenement'] ?? $evenement['date_evenement']);

        if (!empty($titre) && !empty($description) && !empty($type_evenement) && !empty($lieu) && !empty($nb_place) && !empty($date_evenement)) {
            $evenement = new Evenement([
                'id' => $id,
                'titre' => $titre,
                'description' => $description,
                'type_evenement' => $type_evenement,
                'lieu' => $lieu,
                'nb_place' => $nb_place,
                'date_evenement' => $date_evenement
            ]);

            if ($repo->modifierEvenement($evenement)) {
                $message = 'Événement modifié avec succès !';
            } else {
                $error = 'Erreur lors de la modification de l\'événement. Veuillez réessayer.';
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
    <title>Modifier un événement</title>
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
    </style>
</head>
<body>

<div class="form-section">
    <h1>Modifier l'événement</h1>

    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="titre">Titre de l'événement :</label>
            <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($evenement['titre']) ?>">
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($evenement['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="type_evenement">Type d'événement :</label>
            <select name="type_evenement" id="type_evenement" required>
                <option value="">-- Choisissez un type --</option>
                <option value="conférence" <?= ($evenement['type_evenement'] === 'conférence') ? 'selected' : '' ?>>Conférence</option>
                <option value="atelier" <?= ($evenement['type_evenement'] === 'atelier') ? 'selected' : '' ?>>Atelier</option>
                <option value="séminaire" <?= ($evenement['type_evenement'] === 'séminaire') ? 'selected' : '' ?>>Séminaire</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" required value="<?= htmlspecialchars($evenement['lieu']) ?>">
        </div>

        <div class="form-group">
            <label for="nb_place">Nombre de places :</label>
            <input type="number" id="nb_place" name="nb_place" required min="1" value="<?= htmlspecialchars($evenement['nb_place']) ?>">
        </div>

        <div class="form-group">
            <label for="date_evenement">Date de l'événement :</label>
            <input type="date" id="date_evenement" name="date_evenement" required value="<?= htmlspecialchars($evenement['date_evenement']) ?>">
        </div>

        <input type="submit" value="Modifier l'événement">
    </form>

    <a href="ListeEvenement.php" class="back-link">← Retour à la liste</a>
</div>

</body>
</html>
