<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';
require_once '../../src/modele/Evenement.php';

use repository\EvenementRepository;
use modele\Evenement;

$message = '';
$error = '';

try {
    // Connexion à la base
    $database = new Bdd();
    $bdd = $database->getBdd(); // PDO
    $repo = new EvenementRepository($bdd);

    // Vérifie si un ID d'événement est passé dans l'URL
    if (!isset($_GET['id'])) {
        die("ID de l'événement manquant.");
    }

    $id = (int)$_GET['id'];
    $evenement = $repo->getEvenementById($id);

    if (!$evenement) {
        die("Événement introuvable.");
    }

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre'] ?? $evenement->getTitre());
        $description = trim($_POST['description'] ?? $evenement->getDescription());
        $type_evenement = trim($_POST['type_evenement'] ?? $evenement->getTypeEvenement());
        $lieu = trim($_POST['lieu'] ?? $evenement->getLieu());
        $nb_place = trim($_POST['nb_place'] ?? $evenement->getNbPlace());
        $date_evenement = trim($_POST['date_evenement'] ?? $evenement->getDateEvenement());

        if ($titre && $description && $type_evenement && $lieu && $nb_place && $date_evenement) {
            $evenementModifie = new Evenement([
                'id_evenement' => $id,
                'titre' => $titre,
                'description' => $description,
                'type_evenement' => $type_evenement,
                'lieu' => $lieu,
                'nb_place' => $nb_place,
                'date_evenement' => $date_evenement
            ]);

            if ($repo->modifierEvenement($evenementModifie)) {
                $message = "Événement modifié avec succès !";
            } else {
                $error = "Erreur lors de la modification de l'événement. Veuillez réessayer.";
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
    <title>Modifier un événement</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; }
        .form-section { background: #f9f9f9; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { font-weight: bold; }
        input[type="text"], input[type="date"], input[type="number"], textarea, select {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type="submit"] { width: 100%; background: #007bff; color: white; padding: 12px; border: none; border-radius: 4px; font-size: 16px; }
        .message { margin-top: 20px; padding: 12px; border-radius: 4px; font-weight: bold; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #007bff; }
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
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($evenement->getTitre()) ?>">
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($evenement->getDescription()) ?></textarea>
        </div>

        <div class="form-group">
            <label for="type_evenement">Type :</label>
            <select id="type_evenement" name="type_evenement" required>
                <?php
                $types = ['conférence', 'atelier', 'séminaire', 'formation', 'fête'];
                foreach ($types as $type) {
                    $selected = ($evenement->getTypeEvenement() === $type) ? 'selected' : '';
                    echo "<option value=\"$type\" $selected>$type</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" required value="<?= htmlspecialchars($evenement->getLieu()) ?>">
        </div>

        <div class="form-group">
            <label for="nb_place">Nombre de places :</label>
            <input type="number" id="nb_place" name="nb_place" required min="1" value="<?= htmlspecialchars($evenement->getNbPlace()) ?>">
        </div>

        <div class="form-group">
            <label for="date_evenement">Date :</label>
            <input type="date" id="date_evenement" name="date_evenement" required value="<?= htmlspecialchars($evenement->getDateEvenement()) ?>">
        </div>

        <input type="submit" value="Modifier l'événement">
    </form>

    <a href="ListeEvenement.php" class="back-link">← Retour à la liste</a>
</div>

</body>
</html>
