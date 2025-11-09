<?php
session_start();
require_once '../bdd/Bdd.php';
require_once '../repository/UtilisateurRepository.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../vue/Connexion.php");
    exit();
}

$id_utilisateur = $_SESSION['id_utilisateur'];
$utilisateurRepository = new UtilisateurRepository();
$utilisateur = $utilisateurRepository->getUserById($id_utilisateur);

if (!$utilisateur) {
    echo "Utilisateur introuvable.";
    exit();
}
?>


<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Profil Utilisateur</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
</head>
<body>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                <span class="font-weight-bold"><?= htmlspecialchars($utilisateur['prenom']) ?></span>
                <span class="text-black-50"><?= htmlspecialchars($utilisateur['email']) ?></span>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <form method="POST" action="ModifierProfil.php">
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Prénom</label>
                            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Nom</label>
                            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Téléphone</label>
                            <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone']) ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Adresse</label>
                            <input type="text" class="form-control" name="adresse" value="<?= htmlspecialchars($utilisateur['adresse']) ?>">
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" type="submit">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
