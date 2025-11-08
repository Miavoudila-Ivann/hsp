<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Entreprise.php';
require_once '../../src/repository/EntrepriseRepository.php';

use repository\EntrepriseRepository;
use modele\Entreprise;

if (!isset($_POST['ok'])) {
    header('Location: ../vue/AjouterEntreprise.php');
    exit;
}

$nom = trim($_POST['nom_entreprise'] ?? '');
$rue = trim($_POST['rue_entreprise'] ?? '');
$ville = trim($_POST['ville_entreprise'] ?? '');
$cd = trim($_POST['cd_entreprise'] ?? '');
$siteWeb = trim($_POST['site_web'] ?? '');

if ($nom && $rue && $ville && $cd && $siteWeb) {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $entreprise = new Entreprise([
        'id_entreprise' => null,
        'nom_entreprise' => $nom,
        'rue_entreprise' => $rue,
        'ville_entreprise' => $ville,
        'cd_entreprise' => $cd,
        'site_web' => $siteWeb
    ]);

    $repo = new EntrepriseRepository($bdd);

    if ($repo->ajouter($entreprise)) {
        header('Location: ../vue/ListeEntreprise.php');
        exit;
    } else {
        echo "Erreur lors de l'ajout de l'entreprise.";
    }
} else {
    echo "Tous les champs sont obligatoires.";
}
?>
