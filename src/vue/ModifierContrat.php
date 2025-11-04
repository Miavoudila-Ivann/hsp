<?php

use repository\ContratRepository;

session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ContratRepository.php';
require_once __DIR__ . '/../modele/Contrat.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ContratRepository($bdd);

if(!isset($_GET['id'])){
    header("Location: ListeContrats.php");
    exit();
}

$id = (int)$_GET['id'];
$c = $repo->findById($id);

if(!$c){
    header("Location: ListeContrats.php");
    exit();
}

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $contrat = new Contrat();
    $contrat->setIdContrat($id);
    $contrat->setPoste($_POST['poste']);
    $contrat->setDateDebut($_POST['date_debut']);
    $contrat->setDateFin($_POST['date_fin']);
    $contrat->setSalaire($_POST['salaire']);

    if($repo->modifierContrat($contrat)){
        $success = "Contrat modifié avec succès !";
    } else {
        $error = "Erreur lors de la modification.";
    }
}

include __DIR__ . '/header.php';
?>

<h2>Modifier le contrat</h2>

<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <label>Poste : <input type="text" name="poste" value="<?= htmlspecialchars($c['poste']) ?>" required></label><br>
    <label>Date début : <input type="date" name="date_debut" value="<?= $c['date_debut'] ?>" required></label><br>
    <label>Date fin : <input type="date" name="date_fin" value="<?= $c['date_fin'] ?>" required></label><br>
    <label>Salaire (€) : <input type="number" name="salaire" value="<?= $c['salaire'] ?>" required></label><br>

    <button type="submit">Modifier</button>
</form>

<a class="btn" href="../vue/ListeContrat.php">Retour</a>

<?php include __DIR__ . '/footer.php'; ?>
