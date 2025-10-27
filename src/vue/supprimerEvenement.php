<?php
// Activation des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EvenementRepository.php';

use repository\EvenementRepository;

$message = '';
$error = '';

try {
    $database = new Bdd();
    $bdd = $database->getBdd();

    $repo = new EvenementRepository($bdd);

    // Vérifie si un ID d'événement est passé dans l'URL
    if (!isset($_GET['id'])) {
        die('ID de l\'événement manquant.');
    }

    $id = (int)$_GET['id'];

    if ($repo->supprimerEvenement($id)) {
        $message = 'Événement supprimé avec succès ! Vous allez être redirigé.';
        echo '<script>setTimeout(function(){ window.location.href = "ListeEvenement.php"; }, 2000);</script>';
    } else {
        $error = 'Erreur lors de la suppression de l\'événement. Veuillez réessayer.';
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
    <title>Supprimer un événement</title>
</head>
<body>

<h1>Supprimer un événement</h1>

<?php if ($message): ?>
    <div><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

</body>
</html>
