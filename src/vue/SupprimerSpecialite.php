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
    <title>Confirmer la suppression</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; }
        .card {
            max-width: 500px;
            margin: 0 auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { color: #d9534f; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px 10px 0 0;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .btn-danger { background-color: #d9534f; }
        .btn-secondary { background-color: #6c757d; }
    </style>
</head>
<body>

<div class="card">
    <h2>⚠️ Confirmer la suppression</h2>
    <p>Voulez-vous vraiment supprimer la spécialité suivante ?</p>
    <p><strong><?= htmlspecialchars($specialite['libelle']) ?></strong> (ID: <?= $specialite['id_specialite'] ?>)</p>

    <a href="../src/traitement/SupprimerSpecialiteTrt.php?id_specialite=<?= $id ?>" class="btn btn-danger">Oui, supprimer</a>
    <a href="ListeSpecialite.php" class="btn btn-secondary">Annuler</a>
</div>

</body>
</html>