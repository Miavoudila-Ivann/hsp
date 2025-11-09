<?php
session_start();
include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Entreprise</title>
    <style>
        label { display: block; margin-top: 8px; }
        input { width: 300px; padding: 4px; }
        button { margin-top: 10px; padding: 6px 12px; }
    </style>
</head>
<body>
<h2>Ajouter une entreprise</h2>
<form action="../traitement/AjouterEntrepriseTrt.php" method="POST">
    <label>Nom :</label>
    <input type="text" name="nom_entreprise" required>

    <label>Rue :</label>
    <input type="text" name="rue_entreprise" required>

    <label>Ville :</label>
    <input type="text" name="ville_entreprise" required>

    <label>Code Postal :</label>
    <input type="text" name="cd_entreprise" required>

    <label>Site Web :</label>
    <input type="text" name="site_web" required>

    <button type="submit" name="ok">Ajouter</button>

</form>
<form action="ListeEntreprise.php" method="get">
    <button type="submit">Voir la liste des entreprises</button>
</form>
<form action="../../index.php" method="get">
    <button type="submit">Acceuil</button>
</body>
</html>

<?php include __DIR__ . '/footer.php'; ?>
