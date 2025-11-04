<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/repository/MedecinRepository.php';

if (!isset($_GET['id_medecin'])) {
    header('Location: ListeMedecin.php');
    exit();
}

$id = $_GET['id_medecin'];
$db = new Bdd();
$repo = new MedecinRepository($db->getBdd());
$medecin = $repo->trouverParId($id);

if (!$medecin) {
    die("Médecin non trouvé.");
}

$stmt = $db->getBdd()->prepare("
    SELECT 
        s.libelle AS specialite,
        h.nom AS hopital,
        e.nom_etablissement AS etablissement
    FROM medecin m
    LEFT JOIN specialite s ON m.ref_specialite = s.id_specialite
    LEFT JOIN hopital h ON m.ref_hopital = h.id_hopital
    LEFT JOIN etablissement e ON m.ref_etablissement = e.id_etablissement
    WHERE m.id_medecin = ?
");
$stmt->execute([$id]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmer la suppression</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; }
        .card {
            max-width: 600px;
            margin: 0 auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { color: #d9534f; }
        ul { margin: 15px 0; padding-left: 20px; }
        li { margin: 8px 0; }
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
    <p>Êtes-vous sûr de vouloir supprimer définitivement ce médecin ?</p>

    <ul>
        <li><strong>ID :</strong> <?= htmlspecialchars($medecin['id_medecin']) ?></li>
        <li><strong>Spécialité :</strong> <?= htmlspecialchars($info['specialite'] ?? '—') ?></li>
        <li><strong>Hôpital :</strong> <?= htmlspecialchars($info['hopital'] ?? '—') ?></li>
        <li><strong>Établissement :</strong> <?= htmlspecialchars($info['etablissement'] ?? '—') ?></li>
    </ul>

    <a href="../src/traitement/SupprimerMedecinTrt.php?id_medecin=<?= $id ?>" class="btn btn-danger">Oui, supprimer</a>
    <a href="ListeMedecin.php" class="btn btn-secondary">Annuler</a>
</div>

</body>
</html>