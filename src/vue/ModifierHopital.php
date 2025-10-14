<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';
require_once '../../src/modele/Hopital.php';

use repository\HopitalRepository;
use modele\Hopital;

$pdo = (new \Bdd())->getBdd();
$repo = new HopitalRepository($pdo);
$message = $error = '';

if (!isset($_GET['id'])) {
    die('ID manquant');
}

$id = (int)$_GET['id'];
$hopitalData = $pdo->prepare("SELECT * FROM hopital WHERE id_hopital = ?");
$hopitalData->execute([$id]);
$data = $hopitalData->fetch();

if (!$data) {
    die("Hôpital introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $adresse = trim($_POST['adresse_hopital']);
    $ville = trim($_POST['ville_hopital']);

    if ($nom && $adresse && $ville) {
        $hopital = new Hopital([
            'id_hopital' => $id,
            'nom' => $nom,
            'adresse_hopital' => $adresse,
            'ville_hopital' => $ville
        ]);
        if ($repo->modifierHopital($hopital)) {
            $message = "Hôpital modifié avec succès.";
            echo "<script>setTimeout(() => window.location.href = 'ListeHopitaux.php', 1500);</script>";
        } else {
            $error = "Erreur lors de la modification.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Hôpital</title>
    <style>
        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #f3f3f3;
            border-radius: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message { text-align: center; margin-top: 20px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Modifier un hôpital</h2>

<form method="POST">
    <label>Nom</label>
    <input type="text" name="nom" required value="<?= htmlspecialchars($data['nom']) ?>">

    <label>Adresse</label>
    <input type="text" name="adresse_hopital" required value="<?= htmlspecialchars($data['adresse_hopital']) ?>">

    <label>Ville</label>
    <input type="text" name="ville_hopital" required value="<?= htmlspecialchars($data['ville_hopital']) ?>">

    <input type="submit" value="Modifier">
</form>

<?php if ($message): ?>
    <div class="message success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

</body>
</html>
