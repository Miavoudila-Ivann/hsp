<?php
namespace vue; // optionnel si tu veux organiser tes vues
include __DIR__ . '/header.php';

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../modele/Candidature.php';

use modele\Candidature; // ✅ Import obligatoire pour le namespace

$database = new \Bdd();
$bdd = $database->getBdd();
$repo = new \repository\CandidatureRepository($bdd); // si ton repository est dans namespace repository

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom_candidat'] ?? '');
    $prenom = trim($_POST['prenom_candidat'] ?? '');
    $motivation = trim($_POST['motivation'] ?? '');

    if (!empty($_FILES['cv']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/cv/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        $allowed = ['pdf', 'doc', 'docx'];

        if (in_array(strtolower($extension), $allowed)) {
            $filename = 'cv_' . $ref_utilisateur . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $targetPath)) {
                $cv_path = 'uploads/cv/' . $filename; // chemin relatif pour la BDD
            }
        } else {
            echo "<p style='color:red;'>Format de fichier non autorisé. (PDF, DOC, DOCX uniquement)</p>";
        }
    }

    if (!empty($nom) && !empty($prenom) && !empty($motivation)) {
        // Crée l'objet Candidature
        $candidature = new Candidature(
                null,          // id_candidature
                $motivation,   // motivation
                'En attente',  // statut par défaut
                date('Y-m-d'), // date_candidature
                1,             // ref_offre (à adapter)
                1,              // ref_utilisateur (à adapter)
                $cv_path
        );

        if ($repo->ajouter($candidature)) {
            $message = "Candidature ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout de la candidature.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Candidature</title>
    <style>
        /* ton style inchangé */
    </style>
</head>
<body>
<div class="form-section">
    <h1>Ajouter une Candidature</h1>

    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom_candidat" id="nom" required value="<?= htmlspecialchars($_POST['nom_candidat'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom_candidat" id="prenom" required value="<?= htmlspecialchars($_POST['prenom_candidat'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="motivation">Motivation :</label>
            <textarea name="motivation" id="motivation" required><?= htmlspecialchars($_POST['motivation'] ?? '') ?></textarea>
        </div>

        <input type="submit" value="Ajouter la candidature">
    </form>

    <a href="../../index.php" class="back-link">← Retour à l'accueil</a>
</div>
</body>
</html>
