<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vue/ListeChambres.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Chambre.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ChambreRepository($bdd);

$numero     = trim($_POST['numero'] ?? '');
$ref_hopital = (int)($_POST['ref_hopital'] ?? 0);

if (empty($numero) || $ref_hopital <= 0) {
    header('Location: ../vue/CreeChambre.php');
    exit();
}

$chambre = new Chambre(null, $numero, 1, $ref_hopital);
$repo->ajouter($chambre);

header('Location: ../vue/ListeChambres.php');
exit();
?>
