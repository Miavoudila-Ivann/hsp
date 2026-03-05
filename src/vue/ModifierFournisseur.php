<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/FournisseurRepository.php';

use repository\FournisseurRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new FournisseurRepository($bdd);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ListeFournisseurs.php');
    exit();
}

$fournisseur = $repo->findById((int)$_GET['id']);
if (!$fournisseur) {
    header('Location: ListeFournisseurs.php');
    exit();
}

$title = "Modifier un fournisseur";
include __DIR__ . '/header.php';
?>

<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display:block; font-weight:bold; margin-bottom:5px; color:#555; }
    .btn-submit { background:#17a2b8; color:white; padding:10px 20px; border:none; border-radius:5px; font-size:15px; font-weight:bold; cursor:pointer; width:100%; }
    .btn-submit:hover { background:#138496; }
    .btn-retour { color:#17a2b8; text-decoration:none; display:inline-block; margin-top:10px; }
</style>

<h2>Modifier un fournisseur</h2>

<form method="post" action="../traitement/ModifierFournisseurTrt.php">
    <input type="hidden" name="id_fournisseur" value="<?= (int)$fournisseur->getIdFournisseur() ?>">

    <div class="form-group">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required
               value="<?= htmlspecialchars($fournisseur->getNom()) ?>">
    </div>
    <div class="form-group">
        <label for="contact">Contact</label>
        <input type="text" id="contact" name="contact"
               value="<?= htmlspecialchars($fournisseur->getContact() ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($fournisseur->getEmail() ?? '') ?>">
    </div>
    <button type="submit" class="btn-submit">Enregistrer les modifications</button>
</form>

<a href="ListeFournisseurs.php" class="btn-retour">← Retour à la liste</a>

<?php include __DIR__ . '/footer.php'; ?>
