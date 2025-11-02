<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../modele/Candidature.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$cRepo = new CandidatureRepository($bdd);

if(!isset($_GET['id'])){
    header("Location: ListeCandidatures.php");
    exit();
}

$id = (int)$_GET['id'];
$candidatures = $cRepo->findAll();
$c = null;
foreach($candidatures as $cand){
    if($cand['id_candidature'] === $id){
        $c = $cand;
        break;
    }
}

if(!$c){
    header("Location: ListeCandidatures.php");
    exit();
}

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $statut = $_POST['statut'] ?? $c['statut'];
    if($cRepo->modifierStatut($id, $statut)){
        $success = "Statut modifié avec succès.";
        $c['statut'] = $statut;
    } else {
        $error = "Erreur lors de la modification.";
    }
}

include __DIR__ . '/header.php';
?>

<h2>Modifier la candidature</h2>

<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <label>Statut :
        <select name="statut">
            <option value="en attente" <?= $c['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
            <option value="acceptée" <?= $c['statut'] === 'acceptée' ? 'selected' : '' ?>>Acceptée</option>
            <option value="refusée" <?= $c['statut'] === 'refusée' ? 'selected' : '' ?>>Refusée</option>
        </select>
    </label><br>
    <button type="submit">Modifier</button>
</form>

<a class="btn" href="ListeCandidatures.php">Retour</a>

<?php include __DIR__ . '/footer.php'; ?>
