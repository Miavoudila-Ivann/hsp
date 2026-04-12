<?php
/**
 * Traite le formulaire de publication d'une offre d'emploi.
 * Tous les champs sont obligatoires (titre, description, mission, salaire, type, état,
 * entreprise, date de publication). L'offre est associée à l'utilisateur connecté.
 * Redirige vers la liste des offres après succès.
 */
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/repository/OffreRepository.php';

use repository\OffreRepository;
use modele\Offre;

// Rejet si le formulaire n'a pas été soumis
if (!isset($_POST['ok'])) {
    header('Location: ../vue/AjouterOffre.php');
    exit;
}

$titre            = trim($_POST['titre'] ?? '');
$description      = trim($_POST['description'] ?? '');
$mission          = trim($_POST['mission'] ?? '');
$salaire          = trim($_POST['salaire'] ?? '');
$type_offre       = trim($_POST['type_offre'] ?? '');
$etat             = trim($_POST['etat'] ?? '');
$ref_entreprise   = trim($_POST['ref_entreprise'] ?? '');
$date_publication = trim($_POST['date_publication'] ?? '');

// Vérification que tous les champs obligatoires sont renseignés
if ($titre && $description && $mission && $salaire && $type_offre && $etat && $ref_entreprise && $date_publication) {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $offre = new Offre([
        'id_offre'         => null,
        'titre'            => $titre,
        'description'      => $description,
        'mission'          => $mission,
        'salaire'          => $salaire,
        'type_offre'       => $type_offre,
        'etat'             => $etat,
        // Liaison avec l'utilisateur connecté ayant publié l'offre
        'ref_utilisateur'  => $_SESSION['id_utilisateur'] ?? null,
        'ref_entreprise'   => $ref_entreprise,
        'date_publication' => $date_publication
    ]);

    $repo = new OffreRepository($bdd);

    // Insertion en base de données
    if ($repo->ajouter($offre)) {
        header('Location: ../vue/ListeOffre.php');
        exit;
    } else {
        echo "Erreur lors de l'ajout.";
    }
} else {
    echo "Tous les champs sont obligatoires.";
}
?>
