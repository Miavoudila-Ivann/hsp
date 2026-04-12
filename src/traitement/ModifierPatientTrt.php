<?php
/**
 * Traitement de modification d'un patient.
 * Réservé aux secrétaires et administrateurs.
 * Valide les champs obligatoires (nom, prénom, numéro de sécurité sociale),
 * récupère le patient existant en base, applique les modifications via les setters,
 * puis persiste la mise à jour. Redirige toujours vers la liste des patients.
 */
session_start();

// Contrôle d'accès : seuls les secrétaires et admins sont autorisés
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

// Accepte uniquement les requêtes POST
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

// Récupération et nettoyage des champs du formulaire
$id_patient = (int)($_POST['id_patient'] ?? 0);
$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$num_secu   = trim($_POST['num_secu'] ?? '');
$email      = trim($_POST['email'] ?? '');
$telephone  = trim($_POST['telephone'] ?? '');
$adresse    = trim($_POST['adresse'] ?? '');

// Vérifie les champs obligatoires
if ($id_patient <= 0 || empty($nom) || empty($prenom) || empty($num_secu)) {
    header('Location: ../vue/ListePatients.php');
    exit();
}

// Vérifie que le patient existe en base avant de modifier
$patient = $repo->findById($id_patient);
if ($patient === null) {
    header('Location: ../vue/ListePatients.php');
    exit();
}

// Application des nouvelles valeurs sur l'objet patient
$patient->setNom($nom);
$patient->setPrenom($prenom);
$patient->setNumSecu($num_secu);
$patient->setEmail($email);
$patient->setTelephone($telephone);
$patient->setAdresse($adresse);

// Persistance de la mise à jour en base de données
$repo->modifier($patient);

header('Location: ../vue/ListePatients.php');
exit();
?>
