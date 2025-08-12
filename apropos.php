<?php
// actualites.php

// Connexion à la base de données (à adapter selon ta config)
$host = 'localhost';
$db = 'sitevitrinedb';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Récupérer les actualités actives, les plus récentes en premier
$stmt = $pdo->prepare("SELECT * FROM actualites WHERE statut = 'actif' ORDER BY date_publication DESC");
$stmt->execute();
$actualites = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Actualités | Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/css/custom-apropos.css" rel="stylesheet" />
  <style>
    /* Reset & base */
    body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--bg);
      color: var(--text);
      transition: background-color 0.4s, color 0.4s;
    }
    .carousel-caption {
      background: rgba(0, 0, 0, 0.6);
      padding: 1.5rem;
      border-radius: 0.75rem;
      max-height: 200px;
      overflow-y: auto;
      bottom: 20px;
    }
    .carousel-item {
      height: 600px;
    }
    .carousel-item img {
      object-fit: cover;
      height: 100%;
      width: 100%;
      cursor: pointer;
      max-height: 600px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <!-- Navbar (tu peux réutiliser ta navbar ici) -->
<?php session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-navbar shadow-sm py-3" style="background-color: #002e5d ; backdrop-filter: blur(10px);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="index.php">
      <img src="img/logo.jpg" alt="Logo" class="rounded-2 me-2" style="height: 40px;"> Betuole Académie
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link active" href="apropos.php">A propos</a></li>
        <li class="nav-item"><a class="nav-link" href="actualites.php">Actualités</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="parent/admin/ges_formations/catalogue.php">Formations</a></li> 
      </ul>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="parent\apprenant\dashboard_app.php" class="nav-link text-light text-decoration-none ms-3">Dashboard user</a>
          <a href="parent/auth/logout.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i> Se Déconnecter
          </a>
        <?php else: ?>
          <a href="parent/auth/register.php" class="btn btn-outline-light ms-3">Créer un compte</a>
        <?php endif; ?>
    </div>
  </div>
  
</nav>

  
  
  <br><br>
      <div class="carousel-item">
        <img src="img/actu2.jpg" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <small class="bg-dark text-white px-2 py-1 rounded">Mise à jour par l'administrateur</small>
          <h5>Certifications disponibles</h5>
          <p>Recevez une attestation reconnue à la fin de chaque formation.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="img/actu3.jpg" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <small class="bg-dark text-white px-2 py-1 rounded">Mise à jour par l'administrateur</small>
          <h5>100% en ligne</h5>
          <p>Apprenez à votre rythme depuis n'importe où.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselActualites" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselActualites" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Suivant</span>
    </button>
  </div>

<!-- SECTION A PROPOS -->
<section class="section-apropos">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h2 class="mb-4">Qui sommes-nous ?</h2>
        <p>
          <strong>Betuole Académie</strong> est un centre de formation professionnelle moderne, innovant et orienté vers l'excellence. Nous proposons des <strong>formations certifiantes</strong> dans plusieurs domaines, accessibles en ligne et en présentiel. Notre mission est de permettre à chaque apprenant de développer des compétences solides et adaptées au monde professionnel d'aujourd'hui.
        </p>
        <p>
          Avec une plateforme intuitive, une gestion transparente des inscriptions et des paiements en ligne, <strong>Betuole Académie</strong> vous offre une expérience d'apprentissage unique, flexible et fiable. Rejoignez une communauté d'apprenants engagés et transformez votre avenir avec nous.
        </p>
        <p>
          Notre vision est de devenir la référence en formation professionnelle en Afrique francophone, en offrant des parcours adaptés aux besoins du marché et en favorisant l'inclusion et l'égalité des chances.
        </p>
        <p>
          Nous valorisons l'innovation pédagogique, l'accompagnement personnalisé et l'excellence dans chaque formation. Notre équipe d'experts passionnés s'engage à vous fournir les meilleurs outils et ressources pour réussir.
        </p>

        <div class="row apropos-icons mt-5">
          <div class="col-md-4 icon-box">
            <i class="bi bi-laptop"></i>
            <h5>Formations en ligne</h5>
            <p>Accédez à nos cours depuis n'importe où, à votre rythme.</p>
          </div>
          <div class="col-md-4 icon-box">
            <i class="bi bi-credit-card"></i>
            <h5>Paiement sécurisé</h5>
            <p>Transactions fiables et sécurisées pour votre tranquillité d'esprit.</p>
          </div>
          <div class="col-md-4 icon-box">
            <i class="bi bi-award"></i>
            <h5>Certifications officielles</h5>
            <p>Obtenez des attestations reconnues par les professionnels du secteur.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATISTICS SECTION -->
<section class="stats-section" style="background-color: #e9f2fb; padding: 60px 0; text-align: center;">
  <div class="container">
    <h3 style="font-weight: 700; color: #003669; margin-bottom: 40px;">Quelques chiffres clés</h3>
    <div class="d-flex justify-content-center gap-5 flex-wrap">
      <div style="font-size: 1.5rem; color: #007bff; font-weight: 600;">
        <span style="font-size: 2.5rem; font-weight: 700;">1500+</span><br>Apprenants formés
      </div>
      <div style="font-size: 1.5rem; color: #007bff; font-weight: 600;">
        <span style="font-size: 2.5rem; font-weight: 700;">25</span><br>Formations disponibles
      </div>
      <div style="font-size: 1.5rem; color: #007bff; font-weight: 600;">
        <span style="font-size: 2.5rem; font-weight: 700;">98%</span><br>Taux de satisfaction
      </div>
      <div style="font-size: 1.5rem; color: #007bff; font-weight: 600;">
        <span style="font-size: 2.5rem; font-weight: 700;">10</span><br>Années d'expérience
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section class="testimonials-section" style="padding: 60px 0; background-color: #fff;">
  <div class="container">
    <h3 class="text-center mb-5" style="font-weight: 700; color: #003669;">Ce que disent nos apprenants</h3>
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="7000">
      <div class="carousel-inner">

        <div class="carousel-item active">
          <div class="d-flex flex-column align-items-center text-center px-4">
            <i class="bi bi-person-circle" style="font-size: 5rem; color: #007bff;"></i>
            <p class="fst-italic mt-3" style="max-width: 600px; color: #555;">
              "Betuole Académie m'a permis d'acquérir des compétences pratiques et reconnues qui ont boosté ma carrière. L'équipe est très professionnelle et disponible."
            </p>
            <h5 class="fw-bold text-primary">Marie T.</h5>
          </div>
        </div>

        <div class="carousel-item">
          <div class="d-flex flex-column align-items-center text-center px-4">
            <i class="bi bi-person-circle" style="font-size: 5rem; color: #007bff;"></i>
            <p class="fst-italic mt-3" style="max-width: 600px; color: #555;">
              "Les formations sont bien structurées et accessibles en ligne, ce qui m'a permis de concilier travail et apprentissage efficacement."
            </p>
            <h5 class="fw-bold text-primary">Jean M.</h5>
          </div>
        </div>

        <div class="carousel-item">
          <div class="d-flex flex-column align-items-center text-center px-4">
            <i class="bi bi-person-circle" style="font-size: 5rem; color: #007bff;"></i>
            <p class="fst-italic mt-3" style="max-width: 600px; color: #555;">
              "Le processus d'inscription et de paiement est simple et sécurisé. Je recommande vivement Betuole Académie à tous ceux qui veulent se former sérieusement."
            </p>
            <h5 class="fw-bold text-primary">Amina K.</h5>
          </div>
        </div>

        <div class="carousel-item">
          <div class="d-flex flex-column align-items-center text-center px-4">
            <i class="bi bi-person-circle" style="font-size: 5rem; color: #007bff;"></i>
            <p class="fst-italic mt-3" style="max-width: 600px; color: #555;">
              "Grâce à Betuole Académie, j'ai pu changer de carrière et trouver un emploi dans un domaine qui me passionne. L'accompagnement est excellent."
            </p>
            <h5 class="fw-bold text-primary">Sophie L.</h5>
          </div>
        </div>

        <div class="carousel-item">
          <div class="d-flex flex-column align-items-center text-center px-4">
            <i class="bi bi-person-circle" style="font-size: 5rem; color: #007bff;"></i>
            <p class="fst-italic mt-3" style="max-width: 600px; color: #555;">
              "Les formateurs sont très compétents et disponibles. Les cours en ligne sont interactifs et bien conçus."
            </p>
            <h5 class="fw-bold text-primary">Marc D.</h5>
          </div>
        </div>

      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #007bff; border-radius: 50%;"></span>
        <span class="visually-hidden">Précédent</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #007bff; border-radius: 50%;"></span>
        <span class="visually-hidden">Suivant</span>
      </button>
    </div>
  </div>
</section>

<!-- CALL TO ACTION SECTION -->
<section class="cta-section" style="background-color: #002e5d; color: #fff; padding: 60px 0; text-align: center;">
  <div class="container">
    <h3 style="font-weight: 700; font-size: 2.5rem; margin-bottom: 20px;">Prêt à transformer votre avenir ?</h3>
    <p style="font-size: 1.25rem; margin-bottom: 30px;">Rejoignez Betuole Académie dès aujourd'hui et accédez à des formations certifiantes adaptées à vos ambitions.</p>
    <a href="parent/auth/register.php" class="btn btn-primary fw-semibold" style="font-size: 1.25rem; padding: 12px 40px; border-radius: 50px;">Créer un compte</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5 class="text-primary mb-3">Betuole Académie</h5>
        <p>Centre de formation professionnelle innovant, ouvert à tous, accessible en ligne 24h/24.</p>
      </div>
      <div class="col-md-4">
        <h5 class="text-primary mb-3">Liens rapides</h5>
        <ul class="list-unstyled">
          <li><a href="formations.php" class="text-white text-decoration-none">Nos formations</a></li>
          <li><a href="inscription.php" class="text-white text-decoration-none">S'inscrire</a></li>
          <li><a href="connexion.php" class="text-white text-decoration-none">Se connecter</a></li>
          <li><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5 class="text-primary mb-3">Contact</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i>Bépanda Maturité, Douala</p>
        <p><i class="bi bi-envelope-fill me-2"></i>contact@betuole-academie.com</p>
        <p><i class="bi bi-telephone-fill me-2"></i>+237 6 79 16 48 01</p>
      </div>
    </div>
    <hr class="bg-light">
    <p class="text-center mb-0">© <?php echo date('Y'); ?> Betuole Académie. Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
