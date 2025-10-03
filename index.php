<?php
$bdd = new PDO(
    'mysql:host=localhost;dbname=hsp;charset=utf8',
    'root',
    ''
);
session_start();

$isConnected = isset($_SESSION['user_id']);
$isAdmin = $isConnected && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Hopital Sud Paris</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/x-icon" href="assets/img/logo.jpg" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<body id="page-top">

<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#page-top">Hopital Sud Paris</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
            Menu <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="vue/Vols_dispo.php">Catalogue</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Rejoint Nous</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="vue/Connexion.html">Connexion</a></li>
                        <li><a class="dropdown-item" href="vue/Inscription.html">Inscription</a></li>
                    </ul>
                    <?php if ($isConnected): ?>
                <li class="nav-item"><a class="nav-link" href="vue/Profil.html">Profil</a></li>
                <?php endif; ?>
                </li>
                <?php if ($isAdmin): ?>
                    <li class="nav-item"><a class="nav-link" href="vue/Administration.html">Admin Programming</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<header class="masthead" style="background-image: url('assets/img/Aéroport 2.jpg'); background-size: cover; background-position: center;">
    <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
        <div class="d-flex justify-content-center">
            <div class="text-center">
                <h1 class="mx-auto my-0 text-uppercase">Hopital Sud Paris</h1>
            </div>
        </div>
    </div>
</header>

<section class="about-section text-center" id="about">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-white mb-4">Aéroport Français</h2>
                <p class="text-white-50">Le meilleur et le plus grand Aéroport de France</p>
            </div>
        </div>
    </div>
</section>

<section class="projects-section bg-light" id="projects">
    <div class="container px-4 px-lg-5">
        <div class="row gx-0 mb-4 mb-lg-5 align-items-center">
            <div class="col-xl-8 col-lg-7"><img class="img-fluid" src="assets/img/AéroAvion.jpg" alt="..." /></div>
            <div class="col-xl-4 col-lg-5">
                <div class="featured-text text-center text-lg-left">
                    <h4>Plusieurs avions à disposition</h4>
                    <p class="text-black-50 mb-0">Aéroport Dugny le plus grand Aéroport de France situé à Dugny, avec plus de 50 avions à votre disposition.</p>
                </div>
            </div>
        </div>
        <div class="row gx-0 mb-5 mb-lg-0 justify-content-center">
            <div class="col-lg-6"><img class="img-fluid" src="assets/img/AéroTaille.jpg" alt="..." /></div>
            <div class="col-lg-6">
                <div class="bg-black text-center h-100 project">
                    <div class="d-flex h-100">
                        <div class="project-text w-100 my-auto text-center text-lg-left">
                            <h4 class="text-white">Taille de l'aéroport</h4>
                            <p class="mb-0 text-white-50">Aéroport Dugny est un très grand aéroport capable d'accueillir énormément de monde.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-0 justify-content-center">
            <div class="col-lg-6"><img class="img-fluid" src="assets/img/BoutiqueAéro.jpg" alt="..." /></div>
            <div class="col-lg-6 order-lg-first">
                <div class="bg-black text-center h-100 project">
                    <div class="d-flex h-100">
                        <div class="project-text w-100 my-auto text-center text-lg-right">
                            <h4 class="text-white">Boutique</h4>
                            <p class="mb-0 text-white-50">Aéroport Dugny vous propose aussi d'innombrables boutiques.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-section bg-black">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-4 mb-3">
                <div class="card py-4 h-100 text-center">
                    <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Adresse</h4>
                    <hr class="my-4 mx-auto" />
                    <div class="small text-black-50">5 AV du Général de Gaulle, 93440 Dugny</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card py-4 h-100 text-center">
                    <i class="fas fa-envelope text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Email</h4>
                    <hr class="my-4 mx-auto" />
                    <div class="small text-black-50"><a href="#">aeroportdugny@gmail.com</a></div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card py-4 h-100 text-center">
                    <i class="fas fa-mobile-alt text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Téléphone</h4>
                    <hr class="my-4 mx-auto" />
                    <div class="small text-black-50">+33 199 999 999</div>
                </div>
            </div>
        </div>
        <div class="social d-flex justify-content-center">
            <a class="mx-2" href="#"><i class="fab fa-twitter"></i></a>
            <a class="mx-2" href="#"><i class="fab fa-facebook-f"></i></a>
            <a class="mx-2" href="#"><i class="fab fa-github"></i></a>
        </div>
    </div>
</section>

<footer class="footer bg-black small text-center text-white-50">
    <div class="container px-4 px-lg-5">© 2025 Airport Dugny</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</body>
</html>
