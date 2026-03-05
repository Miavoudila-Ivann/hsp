<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

$title = "Ajouter un patient";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; resize: vertical; }
</style>

<h2>Ajouter un patient</h2>

<a class="btn-retour" href="ListePatients.php">&larr; Retour à la liste</a>

<form action="../traitement/AjoutPatientTrt.php" method="POST">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" placeholder="Nom du patient" required>

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" placeholder="Prénom du patient" required>

    <label for="num_secu">Numéro de sécurité sociale</label>
    <input type="text" id="num_secu" name="num_secu" placeholder="Ex : 1 85 05 75 108 142 17" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="email@exemple.fr">

    <label for="telephone">Téléphone</label>
    <input type="text" id="telephone" name="telephone" placeholder="Ex : 0601020304">

    <label for="adresse">Adresse</label>
    <textarea id="adresse" name="adresse" rows="2" placeholder="Adresse complète"></textarea>

    <button type="submit">Enregistrer le patient</button>
</form>

<?php include __DIR__ . '/footer.php'; ?>
