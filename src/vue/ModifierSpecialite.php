<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/repository/SpecialiteRepository.php';

if (!isset($_GET['id_specialite'])) {
    header('Location: ListeSpecialite.php');
    exit();
}

$id = $_GET['id_specialite'];
$db = new Bdd();
$repo = new SpecialiteRepository($db->getBdd());
$specialite = $repo->trouverParId($id);

if (!$specialite) {
    die("Spécialité non trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Spécialité</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; font-weight: bold; }
        input[type="text"] { width: 250px; padding: 6px; }
        .btn {
            padding: 8px 16px;
            margin: 5px 10px 0 0;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            display: inline-block;
            text-align: center;
        }
        .btn-save { background-color: #17a2b8; }
        .btn-cancel { background-color: #6c757d; }
    </style>
</head>
<body>

<h2>Modifier la Spécialité #<?= htmlspecialchars($specialite['id_specialite']) ?></h2>

<form method="POST" action="../src/traitement/ModifierSpecialiteTrt.php">
    <input type="hidden" name="id_specialite" value="<?= $specialite['id_specialite'] ?>">

    <div class="form-group">
        <label>Libellé :</label>
        <input type="text" name="libelle" value="<?= htmlspecialchars($specialite['libelle']) ?>" required>
    </div>

    <div class="form-group">
        <button type="submit" name="ok" class="btn btn-save">Mettre à jour</button>
        <a href="ListeSpecialite.php" class="btn btn-cancel">Annuler</a>
    </div>
</form>

</body>
</html>