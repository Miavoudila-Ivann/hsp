<?php
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
$offres = $offreRepo->findAll();

// Vérification session utilisateur
$ref_utilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$ref_utilisateur) die("Utilisateur non connecté !");

// ✅ Nom hôpital sécurisé pour la navbar
$hospital_name = htmlspecialchars($_SESSION["hospital_name"] ?? "Hopital Sud Paris");

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Candidature</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ TAILWIND POUR LA NAVBAR -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            padding-top: 110px; /* espace pour la navbar */
        }

        .form-section {
            background: white;
            max-width: 700px;
            margin: 0 auto 50px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #1d4ed8;
        }

        .success {
            color: #16a34a;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .error {
            color: #dc2626;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-600 to-cyan-500 p-2 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-blue-700">
                    <?= $hospital_name ?>
                </span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="../../index.php" class="text-gray-700 hover:text-blue-600 font-medium">Accueil</a>
                <a href="ListeEtablissement.php" class="text-gray-700 hover:text-blue-600 font-medium">Etablissements</a>
                <a href="Profil.php" class="text-gray-700 hover:text-blue-600 font-medium">Profil</a>
            </div>
        </div>
    </div>
</nav>
<!-- ✅ FIN NAVBAR -->


<div class="form-section">
    <h1>Ajouter une Candidature</h1>

    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Offre :</label>
        <select name="ref_offre" required>
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

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Motivation :</label>
        <textarea name="motivation" required></textarea>

        <label>CV :</label>
        <input type="file" name="cv" accept=".pdf,.doc,.docx">

        <button type="submit">Postuler</button>

    </form>
</div>

</body>
</html>
