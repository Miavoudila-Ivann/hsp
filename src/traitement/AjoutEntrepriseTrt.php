<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Entreprise.php';
require_once '../../src/repository/EntrepriseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {

    $nom = trim($_POST['nom_entreprise'] ?? '');
    $rue = trim($_POST['rue_entreprise'] ?? '');
    $ville = trim($_POST['ville_entreprise'] ?? '');
    $cd = trim($_POST['cd_entreprise'] ?? '');
    $site = trim($_POST['site_web'] ?? '');

    if ($nom && $rue && $ville && $cd && $site) {
        $entreprise = new Entreprise(null, $nom, $rue, $ville, $cd, $site);
        $repo = new EntrepriseRepository($bdd);

        if ($repo->ajouter($entreprise)) {
            header('Location: ../../vue/ListeEntreprise.php');
            exit;
        } else {
            echo "❌ Erreur lors de l'ajout de l'entreprise.";
        }
    } else {
        echo "⚠️ Tous les champs sont obligatoires.";
    }
}
?>
