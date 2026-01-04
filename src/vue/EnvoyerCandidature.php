<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../modele/Candidature.php';

if(!isset($_SESSION['id_utilisateur'])){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$cRepo = new CandidatureRepository($bdd);

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $titre = $_POST['titre_poste'] ?? '';
    $desc = $_POST['description'] ?? '';

    if(!$titre || !$desc){
        $error = "Veuillez remplir tous les champs.";
    } else {
        $candidature = new Candidature();
        $candidature->setIdUtilisateur($_SESSION['id_utilisateur']);
        $candidature->setTitrePoste($titre);
        $candidature->setDescription($desc);

        if($cRepo->envoyerCandidature($candidature)){
            $success = "Candidature envoyée avec succès !";
        } else {
            $error = "Erreur lors de l'envoi.";
        }
    }
}

include __DIR__ . '/header.php';
?>

<h2>Envoyer une candidature</h2>

<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<?php if($success) echo "<p style='color:green'>$success</p>"; ?>

<form method="post">
    <label>Titre du poste : <input type="text" name="titre_poste" required></label><br>
    <label>Description : <textarea name="description" required></textarea></label><br>
    <button type="submit">Envoyer</button>
</form>

<a class="btn" href="Candidatures.php">Voir mes candidatures</a>

<style>
    .btn {
        padding: 8px 12px;
        background-color: #2196F3;
        color: white;
        border-radius: 4px;
        text-decoration: none;
    }
    .btn:hover { background-color: #1976D2; }
</style>

<?php include __DIR__ . '/footer.php'; ?>
