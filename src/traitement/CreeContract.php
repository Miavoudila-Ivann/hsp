<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ContratRepository.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../modele/Contrat.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$cRepo = new CandidatureRepository($bdd);
$contratRepo = new ContratRepository($bdd);

$error = "";
$success = "";

$candidatures = $cRepo->findAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $contrat = new Contrat();
    $contrat->setIdCandidature($_POST['id_candidature']);
    $contrat->setPoste($_POST['poste']);
    $contrat->setDateDebut($_POST['date_debut']);
    $contrat->setDateFin($_POST['date_fin']);
    $contrat->setSalaire($_POST['salaire']);

    if($contratRepo->creerContrat($contrat)){
        $success = "Contrat créé avec succès !";
    } else {
        $error = "Erreur lors de la création du contrat.";
    }
}

include __DIR__ . '/header.php';
?>

<h2>Créer un contrat</h2>

<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <label>Candidature :
        <select name="id_candidature" required>
            <?php foreach($candidatures as $cand): ?>
                <option value="<?= $cand['id_candidature'] ?>">
                    <?= htmlspecialchars($cand['prenom'].' '.$cand['nom'].' - '.$cand['titre_poste']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Poste : <input type="text" name="poste" required></label><br>
    <label>Date début : <input type="date" name="date_debut" required></label><br>
    <label>Date fin : <input type="date" name="date_fin" required></label><br>
    <label>Salaire (€) : <input type="number" name="salaire" required></label><br>

    <button type="submit">Créer</button>
</form>

<a class="btn" href="ListeContrats.php">Retour</a>

<?php include __DIR__ . '/footer.php'; ?>
