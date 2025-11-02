<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hopital Sud Paris - Votre Santé, Notre Priorité</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(to right, #2563eb, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-image {
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .scroll-smooth { scroll-behavior: smooth; }
    </style>
</head>
<body class="scroll-smooth">

<?php
// Configuration
$hospital_name = "Hopital Sud Paris";
$established_year = 1985;

// Services disponibles
$services = [
        [
                'icon' => '🚨',
                'title' => 'Urgences 24/7',
                'description' => "Service d'urgence disponible jour et nuit avec des médecins spécialisés."
        ],
        [
                'icon' => '🩺',
                'title' => 'Consultations',
                'description' => 'Consultations médicales générales et spécialisées sur rendez-vous.'
        ],
        [
                'icon' => '❤️',
                'title' => 'Cardiologie',
                'description' => 'Service de cardiologie équipé des technologies les plus avancées.'
        ],
        [
                'icon' => '👶',
                'title' => 'Pédiatrie',
                'description' => 'Soins dédiés aux enfants avec une équipe bienveillante et expérimentée.'
        ],
        [
                'icon' => '🦴',
                'title' => 'Orthopédie',
                'description' => 'Traitement des pathologies osseuses et articulaires.'
        ],
        [
                'icon' => '🧠',
                'title' => 'Neurologie',
                'description' => 'Diagnostic et traitement des maladies du système nerveux.'
        ],
        [
                'icon' => '🔬',
                'title' => 'Laboratoire',
                'description' => 'Analyses médicales avec résultats rapides et précis.'
        ],
        [
                'icon' => '💉',
                'title' => 'Vaccination',
                'description' => 'Centre de vaccination pour tous les âges.'
        ]
];

// Statistiques
$stats = [
        ['number' => '15000+', 'label' => 'Patients par an'],
        ['number' => '200+', 'label' => 'Professionnels'],
        ['number' => '30+', 'label' => 'Spécialités'],
        ['number' => '24/7', 'label' => 'Disponibilité']
];

// Points forts
$features = [
        [
                'icon' => '🏆',
                'title' => 'Excellence Reconnue',
                'description' => 'Certifications nationales et internationales'
        ],
        [
                'icon' => '👥',
                'title' => "Équipe d'Excellence",
                'description' => 'Plus de 200 professionnels hautement qualifiés'
        ],
        [
                'icon' => '⚡',
                'title' => 'Technologie de Pointe',
                'description' => 'Équipements médicaux les plus avancés'
        ]
];

// Informations de contact
$contact = [
        'address' => '15 Avenue de la République',
        'city' => '94250 Gentilly',
        'country' => 'France',
        'phone' => '+33 1 45 67 89 00',
        'emergency' => '15',
        'email_contact' => 'contact@hopitalsudparis.fr',
        'email_rdv' => 'rdv@hopitalsudparis.fr'
];

// Horaires
$hours = [
        'urgences' => 'Ouvert 24h/24, 7j/7',
        'consultations' => [
                'Lundi - Vendredi: 8h00 - 20h00',
                'Samedi: 9h00 - 18h00'
        ]
];
?>

<!-- Navigation -->
<nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-600 to-cyan-500 p-2 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold gradient-text">
                    <?php echo $hospital_name; ?>
                </span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="src/vue/AjoutCandidature.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Candidatures</a>
                <a href="src/vue/ListeEtablissement.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Etablissements</a>
                <a href="#apropos" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">À Propos</a>
                <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">Contact</a>

                <?php if (isset($_SESSION["id"])): ?>
                    <!-- Si connecté -->
                    <div class="flex items-center space-x-4 bg-gray-100 px-4 py-2 rounded-lg">
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">
                                <?= htmlspecialchars($_SESSION["prenom"] . " " . $_SESSION["nom"]) ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?= htmlspecialchars($_SESSION["role"]) ?>
                            </div>
                        </div>
                        <a href="src/vue/deconnexion.php" class="text-red-500 hover:text-red-700 font-medium">
                            Déconnexion
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Si non connecté -->
                    <a href="src/vue/inscription.php" class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 font-medium">
                        Inscription / Connexion
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-3">
            <a href="#accueil" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Accueil</a>
            <a href="#services" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Services</a>
            <a href="#apropos" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">À Propos</a>
            <a href="#contact" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Contact</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="accueil" class="pt-20 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                    Excellence médicale depuis <?php echo $established_year; ?>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                    Votre Santé,
                    <span class="block gradient-text">Notre Priorité</span>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Le plus grand hôpital du sud de Paris offrant des soins de qualité supérieure avec des technologies de pointe et une équipe médicale d'excellence.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#rdv" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-8 py-4 rounded-lg hover:shadow-xl transition-all duration-300 font-medium text-lg">
                        Prendre Rendez-vous
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#services" class="bg-white border-2 border-gray-200 text-gray-700 px-8 py-4 rounded-lg hover:border-blue-600 hover:text-blue-600 transition-all duration-300 font-medium text-lg">
                        Nos Services
                    </a>
                </div>
            </div>
            <div class="relative hero-image">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-3xl transform rotate-3"></div>
                <img
                        src="https://images.pexels.com/photos/236380/pexels-photo-236380.jpeg?auto=compress&cs=tinysrgb&w=800"
                        alt="<?php echo $hospital_name; ?>"
                        class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover"
                />
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">4.9/5</div>
                            <div class="text-sm text-gray-600">Note patients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-gradient-to-r from-blue-600 to-cyan-500 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php foreach ($stats as $stat): ?>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2"><?php echo $stat['number']; ?></div>
                    <div class="text-blue-100 text-sm md:text-base"><?php echo $stat['label']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                Nos Services
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Des Soins Complets et Personnalisés
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Nous offrons une gamme complète de services médicaux avec des équipements de dernière génération.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($services as $service): ?>
                <div class="group bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-blue-500 hover:shadow-xl transition-all duration-300">
                    <div class="text-5xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <?php echo $service['icon']; ?>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $service['title']; ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?php echo $service['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="apropos" class="py-24 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1">
                <img
                        src="https://images.pexels.com/photos/263402/pexels-photo-263402.jpeg?auto=compress&cs=tinysrgb&w=800"
                        alt="Équipe médicale"
                        class="rounded-3xl shadow-2xl w-full h-[500px] object-cover"
                />
            </div>
            <div class="order-1 md:order-2 space-y-6">
                <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                    À Propos
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900">
                    Le Meilleur Hôpital
                    <span class="block text-blue-600">de France</span>
                </h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Fondé en <?php echo $established_year; ?>, l'<?php echo $hospital_name; ?> est devenu une référence en matière de soins de santé en France. Nous combinons expertise médicale, technologies de pointe et approche humaine pour offrir les meilleurs soins possibles.
                </p>
                <div class="space-y-4">
                    <?php foreach ($features as $feature): ?>
                        <div class="flex items-start gap-4">
                            <div class="text-3xl flex-shrink-0">
                                <?php echo $feature['icon']; ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1"><?php echo $feature['title']; ?></h4>
                                <p class="text-gray-600"><?php echo $feature['description']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Appointment Section -->
<section id="rdv" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                Prise de Rendez-vous
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Prenez Rendez-vous
            </h2>
            <p class="text-xl text-gray-600">
                Remplissez le formulaire ci-dessous et notre équipe vous contactera rapidement.
            </p>
        </div>

        <form action="traitement_rdv.php" method="POST" class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8 space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Nom complet *</label>
                    <input type="text" name="nom" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Téléphone *</label>
                    <input type="tel" name="telephone" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Email *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Service souhaité *</label>
                    <select name="service" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                        <option value="">Sélectionnez un service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo htmlspecialchars($service['title']); ?>"><?php echo $service['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Date souhaitée *</label>
                    <input type="date" name="date" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Message (optionnel)</label>
                <textarea name="message" rows="4" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:outline-none transition-colors"></textarea>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-8 py-4 rounded-lg hover:shadow-xl transition-all duration-300 font-semibold text-lg">
                Envoyer ma demande
            </button>
        </form>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-24 bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                Contact
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Nous Sommes Là Pour Vous
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                N'hésitez pas à nous contacter pour toute question ou pour prendre rendez-vous.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">📍</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Adresse</h3>
                <p class="text-gray-600">
                    <?php echo $contact['address']; ?><br />
                    <?php echo $contact['city']; ?><br />
                    <?php echo $contact['country']; ?>
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">📞</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Téléphone</h3>
                <p class="text-gray-600 mb-2">
                    Standard: <?php echo $contact['phone']; ?>
                </p>
                <p class="text-red-600 font-semibold">
                    Urgences: <?php echo $contact['emergency']; ?>
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">✉️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Email</h3>
                <p class="text-gray-600">
                    <?php echo $contact['email_contact']; ?><br />
                    <?php echo $contact['email_rdv']; ?>
                </p>
            </div>
        </div>

        <div class="mt-12 bg-white rounded-2xl p-8">
            <div class="flex items-start gap-4">
                <div class="text-3xl flex-shrink-0">⏰</div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Horaires d'Ouverture</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-gray-600">
                        <div>
                            <p class="font-semibold text-gray-900">Urgences</p>
                            <p><?php echo $hours['urgences']; ?></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Consultations</p>
                            <?php foreach ($hours['consultations'] as $horaire): ?>
                                <p><?php echo $horaire; ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-gradient-to-br from-blue-600 to-cyan-500 p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold"><?php echo $hospital_name; ?></span>
                </div>
                <p class="text-gray-400 leading-relaxed">
                    Le meilleur et le plus grand hôpital du sud de Paris, offrant des soins de qualité supérieure depuis <?php echo $established_year; ?>.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Liens Rapides</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#accueil" class="hover:text-white transition-colors">Accueil</a></li>
                    <li><a href="#services" class="hover:text-white transition-colors">Services</a></li>
                    <li><a href="#apropos" class="hover:text-white transition-colors">À Propos</a></li>
                    <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-4">Services Principaux</h4>
                <ul class="space-y-2 text-gray-400">
                    <?php foreach (array_slice($services, 0, 4) as $service): ?>
                        <li><?php echo $service['title']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $hospital_name; ?>. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                mobileMenu.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>
