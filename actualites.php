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
<?php session_start(); ?>
<!-- Navbar (tu peux réutiliser ta navbar ici) -->
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
        <li class="nav-item"><a class="nav-link" href="apropos.php">A propos</a></li>
        <li class="nav-item"><a class="nav-link active" href="actualites.php">Actualités</a></li>
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


  <main class="container mt-5 pt-5">
    <h1 class="mb-4 text-primary">Actualités</h1>

    <?php if (count($actualites) === 0): ?>
      <div class="alert alert-info">Aucune actualité disponible pour le moment.</div>
    <?php else: ?>
      <div id="actualitesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
          <?php foreach ($actualites as $index => $actu): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
              <img src="parent/admin/ges_actualites/<?= htmlspecialchars($actu['image']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($actu['titre']) ?>" data-bs-toggle="modal" data-bs-target="#imageModal" data-title="<?= htmlspecialchars($actu['titre']) ?>" data-description="<?= nl2br(htmlspecialchars($actu['description'])) ?>" data-image="parent/admin/ges_actualites/<?= htmlspecialchars($actu['image']) ?>">
              <div class="carousel-caption d-none d-md-block">
                <h5><?= htmlspecialchars($actu['titre']) ?></h5>
                <p><?= nl2br(htmlspecialchars($actu['description'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#actualitesCarousel" data-bs-slide="prev" aria-label="Précédent">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#actualitesCarousel" data-bs-slide="next" aria-label="Suivant">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
    <?php endif; ?>

    <!-- Paragraphes de texte supplémentaires après le carrousel -->
    <section class="container mt-5 p-4 shadow-lg rounded bg-white">
      <h2 class="mb-3 text-primary fst-italic text-center">Pourquoi choisir BETUOLE Académie pour votre formation professionnelle ?</h2>
      <hr>
      <p style="text-align: justify; line-height: 1.6; font-size: 1.1rem;">
        Vous êtes-vous déjà demandé comment une formation professionnelle de qualité peut transformer votre carrière et votre vie ? Chez BETUOLE Académie, nous croyons fermement que l'éducation est la clé du succès. Notre centre de formation offre un environnement d'apprentissage dynamique et innovant, conçu pour vous préparer aux exigences du marché du travail moderne. Que vous souhaitiez exceller dans le massage professionnel, le gommage, l'onglerie, les soins du visage, l'esthétique, la manucure ou bien d'autres métiers, nos programmes sont élaborés par des experts passionnés et expérimentés. Chaque cours est structuré pour vous fournir des compétences pratiques et théoriques approfondies, avec un accent particulier sur l'application réelle en milieu professionnel. Nos formateurs utilisent des méthodes pédagogiques modernes, incluant des ateliers pratiques, des démonstrations en direct et un accompagnement personnalisé. En rejoignant BETUOLE Académie, vous bénéficiez non seulement d'une formation de haut niveau, mais aussi d'un réseau professionnel solide qui vous ouvrira de nombreuses portes. Notre engagement envers votre réussite se traduit par un suivi post-formation, des stages en entreprise et un soutien continu pour votre insertion professionnelle. Faites le choix d'une formation qui fait la différence, investissez en vous-même avec BETUOLE Académie.
      </p>
    </section>

    <section class="container mt-5 p-4 shadow-lg rounded bg-white">
      <h2 class="mb-3 text-primary fst-italic text-center">Êtes-vous prêt à transformer votre passion en une carrière florissante avec BETUOLE Académie ?</h2>
      <hr>
      <p style="text-align: justify; line-height: 1.6; font-size: 1.1rem;">
        Imaginez un lieu où votre passion pour les métiers du bien-être et de l'esthétique devient une véritable carrière professionnelle. BETUOLE Académie est ce lieu. Nous comprenons que chaque apprenant est unique, c'est pourquoi nous proposons des formations adaptées à vos besoins, vos objectifs et votre rythme. Nos cursus couvrent un large éventail de disciplines, allant du massage professionnel au soin du visage, en passant par la manucure et l'onglerie, avec un accent sur l'excellence et la qualité. En choisissant BETUOLE Académie, vous accédez à des équipements modernes, des techniques à la pointe et un encadrement bienveillant. Nos formateurs sont des professionnels reconnus qui partagent leur savoir-faire avec passion et rigueur. De plus, notre centre favorise un esprit de communauté et de collaboration, essentiel pour votre épanouissement personnel et professionnel. Nous mettons un point d'honneur à vous préparer non seulement aux compétences techniques, mais aussi aux aspects entrepreneuriaux et relationnels indispensables dans ces métiers. Êtes-vous prêt à franchir le pas et à rejoindre une institution qui valorise votre talent et vous accompagne vers le succès ? BETUOLE Académie est votre partenaire de confiance pour bâtir un avenir prometteur.
      </p>
    </section>
  </main>

  <!-- Modal pour agrandir l'image et afficher la description complète -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" alt="" class="img-fluid mb-3" />
          <p id="modalDescription"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer (réutilise le footer de ton site) -->
  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
      <small>© <?= date('Y') ?> Betuole Académie. Tous droits réservés.</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function (event) {
      var img = event.relatedTarget;
      var title = img.getAttribute('data-title');
      var description = img.getAttribute('data-description');
      var imageSrc = img.getAttribute('data-image');

      var modalTitle = imageModal.querySelector('.modal-title');
      var modalImage = imageModal.querySelector('#modalImage');
      var modalDescription = imageModal.querySelector('#modalDescription');

      modalTitle.textContent = title;
      modalImage.src = imageSrc;
      modalImage.alt = title;
      modalDescription.innerHTML = description.replace(/\n/g, '<br>');

      // Ajuster la taille de l'image dans le modal pour ne pas dépasser la taille de la fenêtre
      modalImage.style.maxHeight = (window.innerHeight * 0.8) + 'px';
      modalImage.style.width = 'auto';

      // Scroll modal description to top on open
      modalDescription.scrollTop = 0;
    });
  </script>
</body>
</html>
