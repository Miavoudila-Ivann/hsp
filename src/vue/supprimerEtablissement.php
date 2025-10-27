<?php
// Activation des erreurs (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import des classes nécessaires
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';

use repository\EtablissementRepository;

$message = ''; // Pour message de succès
$error = '';   // Pour message d'erreur

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $repo = new EtablissementRepository($bdd);

    // Vérifie si un ID d'établissement est passé dans l'URL
    if (!isset($_GET['id'])) {
        die('ID de l\'établissement manquant.');
    }

    $id = (int)$_GET['id'];

    // Suppression de l'établissement
    if ($repo->supprimerEtablissement($id)) {
        $message = 'Établissement supprimé avec succès ! Vous allez être redirigé.';
        echo '<script>setTimeout(function(){ window.location.href = "CreeEtablissement.php"; }, 2000);</script>';
    } else {
        $error = 'Erreur lors de la suppression de l\'établissement. Veuillez réessayer.';
    }
} catch (Exception $e) {
    error_log('Erreur : ' . $e->getMessage());
    $error = "Erreur de connexion à la base de données.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Etablissement</title>
</head>
<body>

<h1>Supprimer un établissement</h1>

<?php if ($message): ?>
    <div><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

</body>
</html>
