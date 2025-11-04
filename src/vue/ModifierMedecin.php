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

$specialites = $repo->getSpecialites();
$hopitaux = $repo->getHopitaux();
$etablissements = $repo->getEtablissements();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Médecin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 180px; font-weight: bold; }
        select { width: 250px; padding: 6px; }
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

<h2>Modifier le Médecin #<?= htmlspecialchars($medecin['id_medecin']) ?></h2>

<form method="POST" action="../src/traitement/ModifierMedecinTrt.php">
    <input type="hidden" name="id_medecin" value="<?= $medecin['id_medecin'] ?>">

    <div class="form-group">
        <label>Spécialité :</label>
        <select name="ref_specialite" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($specialites as $s): ?>
                <option value="<?= $s['id_specialite'] ?>" <?= $s['id_specialite'] == $medecin['ref_specialite'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Hôpital :</label>
        <select name="ref_hopital" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($hopitaux as $h): ?>
                <option value="<?= $h['id_hopital'] ?>" <?= $h['id_hopital'] == $medecin['ref_hopital'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($h['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Établissement :</label>
        <select name="ref_etablissement" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($etablissements as $e): ?>
                <option value="<?= $e['id_etablissement'] ?>" <?= $e['id_etablissement'] == $medecin['ref_etablissement'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nom_etablissement']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" name="ok" class="btn btn-save">Mettre à jour</button>
        <a href="ListeMedecin.php" class="btn btn-cancel">Annuler</a>
    </div>
</form>

</body>
</html>