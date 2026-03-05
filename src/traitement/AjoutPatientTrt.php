<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

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

if (empty($nom) || empty($prenom) || empty($num_secu)) {
    header('Location: ../vue/CreePatient.php');
    exit();
}

$patient = new Patient(null, $nom, $prenom, $num_secu, $email, $telephone, $adresse);
$repo->ajouter($patient);

header('Location: ../vue/ListePatients.php');
exit();
?>
