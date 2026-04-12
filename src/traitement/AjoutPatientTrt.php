<?php
/**
 * Traite le formulaire d'enregistrement d'un nouveau patient.
 * Réservé aux secrétaires et administrateurs.
 * Le nom, prénom et numéro de sécurité sociale sont obligatoires ;
 * email, téléphone et adresse sont optionnels.
 * Redirige vers la liste des patients après succès.
 */
session_start();

// Vérification du rôle : seuls les secrétaires et admins peuvent enregistrer un patient
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

// Rejet des requêtes qui ne sont pas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/ListePatients.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Patient.php';
require_once __DIR__ . '/../repository/PatientRepository.php';

use repository\PatientRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new PatientRepository($bdd);

$nom       = trim($_POST['nom'] ?? '');
$prenom    = trim($_POST['prenom'] ?? '');
$num_secu  = trim($_POST['num_secu'] ?? '');
$email     = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$adresse   = trim($_POST['adresse'] ?? '');

// Validation : nom, prénom et numéro de sécurité sociale sont obligatoires
if (empty($nom) || empty($prenom) || empty($num_secu)) {
    header('Location: ../vue/CreePatient.php');
    exit();
}

// Insertion du patient en base de données
$patient = new Patient(null, $nom, $prenom, $num_secu, $email, $telephone, $adresse);
$repo->ajouter($patient);

header('Location: ../vue/ListePatients.php');
exit();
?>
