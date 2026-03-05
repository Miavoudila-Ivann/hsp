<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ChambreRepository($bdd);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $repo->setDisponible($id, true);
}

header('Location: ../vue/ListeChambres.php');
exit();
?>
