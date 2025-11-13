<?php
// --- Partie PHP ---
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../repository/EntrepriseRepository.php';
require_once __DIR__ . '/../modele/Entreprise.php';
require_once __DIR__ . '/../repository/OffreRepository.php';
require_once __DIR__ . '/../modele/Offre.php';

use repository\CandidatureRepository;
use repository\EntrepriseRepository;
use repository\OffreRepository;

// Connexion à la BDD
$database = new \Bdd();
$bdd = $database->getBdd();
if (!$bdd) die("Connexion à la BDD échouée !");

// Repositories
$candidatureRepo = new CandidatureRepository($bdd);
$entrepriseRepo = new EntrepriseRepository($bdd);
$offreRepo = new OffreRepository($bdd);

// Récupérer toutes les entreprises et offres
$entreprises = $entrepriseRepo->findAll();
$offres = $offreRepo->findAll(); // objets Offre

// Vérification session utilisateur
$ref_utilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$ref_utilisateur) die("Utilisateur non connecté !");

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $motivation = trim($_POST['motivation'] ?? '');
    $ref_offre = $_POST['ref_offre'] ?? '';

    $cv_path = null;
    if (!empty($_FILES['cv']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/cv/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        $allowed = ['pdf','doc','docx'];
        if (in_array(strtolower($extension), $allowed)) {
            $filename = 'cv_'.$ref_utilisateur.'_'.time().'.'.$extension;
            $targetPath = $uploadDir.$filename;
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $targetPath)) {
                $cv_path = 'uploads/cv/'.$filename;
            }
        } else {
            $error = "Format de fichier non autorisé. (PDF, DOC, DOCX uniquement)";
        }
    }

    // Trouver l’entreprise liée à l’offre
    $ref_entreprise = null;
    foreach ($offres as $offre) {
        if ($offre->getIdOffre() == $ref_offre) {
            $ref_entreprise = $offre->getRefEntreprise();
            break;
        }
    }

    if ($nom && $prenom && $motivation && $ref_offre) {
        $candidature = new \modele\Candidature(
                null,
                $motivation,
                'En attente',
                date('Y-m-d'),
                $ref_entreprise,
                $ref_utilisateur,
                $cv_path
        );

        if ($candidatureRepo->ajouter($candidature)) {
            $message = "Candidature ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout de la candidature.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}

include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Candidature</title>
</head>
<body>
<div class="form-section">
    <h1>Ajouter une Candidature</h1>

    <?php if ($message): ?>
        <div style="color:green;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="offre">Offre :</label>
            <select name="ref_offre" id="offre" required>
                <option value="">-- Sélectionnez une offre --</option>
                <?php foreach ($offres as $offre): ?>
                    <?php
                    $nomEntreprise = '';
                    foreach ($entreprises as $entreprise) {
                        if ($entreprise->getId() == $offre->getRefEntreprise()) {
                            $nomEntreprise = $entreprise->getNom();
                            break;
                        }
                    }
                    ?>
                    <option value="<?= htmlspecialchars($offre->getIdOffre()) ?>">
                        <?= htmlspecialchars($offre->getTitre()) ?> (<?= htmlspecialchars($nomEntreprise) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>
        </div>

        <div>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required>
        </div>

        <div>
            <label for="motivation">Motivation :</label>
            <textarea name="motivation" id="motivation" required></textarea>
        </div>

        <div>
            <label for="cv">CV :</label>
            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx">
        </div>

        <button type="submit">Postuler</button>
    </form>
</div>
</body>
</html>
