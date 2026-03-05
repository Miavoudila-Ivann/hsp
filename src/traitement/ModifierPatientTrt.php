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

$id_patient = (int)($_POST['id_patient'] ?? 0);
$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$num_secu   = trim($_POST['num_secu'] ?? '');
$email      = trim($_POST['email'] ?? '');
$telephone  = trim($_POST['telephone'] ?? '');
$adresse    = trim($_POST['adresse'] ?? '');

if ($id_patient <= 0 || empty($nom) || empty($prenom) || empty($num_secu)) {
    header('Location: ../vue/ListePatients.php');
    exit();
}

$patient = $repo->findById($id_patient);
if ($patient === null) {
    header('Location: ../vue/ListePatients.php');
    exit();
}

$patient->setNom($nom);
$patient->setPrenom($prenom);
$patient->setNumSecu($num_secu);
$patient->setEmail($email);
$patient->setTelephone($telephone);
$patient->setAdresse($adresse);

$repo->modifier($patient);

header('Location: ../vue/ListePatients.php');
exit();
?>
