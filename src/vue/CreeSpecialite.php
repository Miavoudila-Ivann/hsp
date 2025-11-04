<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Spécialité</title>
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
        .btn-save { background-color: #28a745; }
        .btn-cancel { background-color: #6c757d; }
    </style>
</head>
<body>

<h2>Ajouter une Spécialité</h2>

<form method="POST" action="../src/traitement/AjoutSpecialiteTrt.php">
    <div class="form-group">
        <label>Libellé :</label>
        <input type="text" name="libelle" required>
    </div>

    <div class="form-group">
        <button type="submit" name="ok" class="btn btn-save">Enregistrer</button>
        <a href="ListeSpecialite.php" class="btn btn-cancel">Annuler</a>
    </div>
</form>

</body>
</html>