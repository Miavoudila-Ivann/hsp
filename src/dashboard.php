<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #e9f0f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 100px;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION["user"]) ?> !</h1>
    <p>Vous êtes connecté.</p>
    <a href="logout.php">Se déconnecter</a>
</div>

</body>
</html>
