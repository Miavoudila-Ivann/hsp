<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Entreprise.php';
require_once '../../src/repository/EntrepriseRepository.php';
include __DIR__ . '/header.php';

use modele\Entreprise;
use repository\EntrepriseRepository;

if (!isset($_GET['id'])) {
    header('Location: ListeEntreprise.php');
    exit;
}

$id = (int) $_GET['id'];
$database = new Bdd();
$bdd = $database->getBdd();
$repo = new EntrepriseRepository($bdd);

// Récupérer l'entreprise existante
$entreprise = $repo->findById($id); // findById doit retourner un objet Entreprise
if (!$entreprise) {
    echo "Entreprise introuvable.";
    exit;
}

// Gestion du formulaire
$error = '';
if (isset($_POST['ok'])) {
    $nom = trim($_POST['nom_entreprise'] ?? '');
    $rue = trim($_POST['rue_entreprise'] ?? '');
    $ville = trim($_POST['ville_entreprise'] ?? '');
    $cd = trim($_POST['cd_entreprise'] ?? '');
    $siteWeb = trim($_POST['site_web'] ?? '');

    if ($nom && $rue && $ville && $cd && $siteWeb) {
        $entreprise->setNom($nom);
        $entreprise->setRue($rue);
        $entreprise->setVille($ville);
        $entreprise->setCd((int)$cd);
        $entreprise->setSiteWeb($siteWeb);

        if ($repo->modifierEntreprise($entreprise)) {
            header('Location: ListeEntreprise.php');
            exit;
        } else {
            $error = "Erreur lors de la modification.";
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
    <title>Modifier Entreprise</title>
</head>
<body>
<h2>Modifier l'entreprise</h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form action="" method="POST">
    <label>Nom :</label>
    <input type="text" name="nom_entreprise" value="<?= htmlspecialchars($entreprise->getNom()) ?>" required><br>

    <label>Rue :</label>
    <input type="text" name="rue_entreprise" value="<?= htmlspecialchars($entreprise->getRue()) ?>" required><br>

    <label>Ville :</label>
    <input type="text" name="ville_entreprise" value="<?= htmlspecialchars($entreprise->getVille()) ?>" required><br>

    <label>Code Postal :</label>
    <input type="text" name="cd_entreprise" value="<?= htmlspecialchars($entreprise->getCd()) ?>" required><br>

    <label>Site Web :</label>
    <input type="text" name="site_web" value="<?= htmlspecialchars($entreprise->getSiteWeb()) ?>" required><br>

    <button type="submit" name="ok">Modifier</button>
</form>

<a href="ListeEntreprise.php">Retour à la liste des entreprises</a>
</body>
</html>
