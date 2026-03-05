<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

$title = "Ajouter un fournisseur";
include __DIR__ . '/header.php';
?>

<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display:block; font-weight:bold; margin-bottom:5px; color:#555; }
    .btn-submit { background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; font-size:15px; font-weight:bold; cursor:pointer; width:100%; }
    .btn-submit:hover { background:#218838; }
    .btn-retour { color:#17a2b8; text-decoration:none; display:inline-block; margin-top:10px; }
</style>

<h2>Ajouter un fournisseur</h2>

<form method="post" action="../traitement/AjoutFournisseurTrt.php">
    <div class="form-group">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required placeholder="Nom du fournisseur">
    </div>
    <div class="form-group">
        <label for="contact">Contact</label>
        <input type="text" id="contact" name="contact" placeholder="Nom du contact">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="email@fournisseur.com">
    </div>
    <button type="submit" class="btn-submit">Enregistrer le fournisseur</button>
</form>

<a href="ListeFournisseurs.php" class="btn-retour">← Retour à la liste</a>

<?php include __DIR__ . '/footer.php'; ?>
