<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil | Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/custom-index-style.css">
  <style>
    /* Loading overlay styles */
    #loadingOverlay {
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      background: linear-gradient(135deg, #2a9df4, #1d78d6);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1050;
      flex-direction: column;
      color: white;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.5rem;
      overflow: hidden;
    }
    #loadingOverlay .spinner {
      width: 5rem;
      height: 5rem;
      border: 0.6rem solid rgba(255, 255, 255, 0.2);
      border-top-color: #ffffff;
      border-radius: 50%;
      animation: spin 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      margin-bottom: 1.2rem;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.7);
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- Loading overlay -->
  <div id="loadingOverlay">
    <div class="spinner"></div>
    Chargement...
  </div>

<?php session_start(); ?>
<!-- NAVBAR MODERNE AVEC EFFET -->
<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top navbar-glass">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="#">
      <img src="img/logo.jpg" alt="Logo" class="rounded-2 me-2" style="height: 40px;">
      Betuole <span class="text-info">Academy</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
       <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="apropos.php">A propos</a></li>
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


<!-- ===== HERO SECTION ===== -->
<section class="hero d-flex align-items-center justify-content-center text-center text-white">
  <div class="overlay"></div>
  <div class="hero-content container">
    <h1 class="display-4 fw-bold" id="colorCycleTitle">Bienvenue à Betuole Académie</h1>
    <p class="lead typewriter-text" id="typewriter-text"></p>
    <button type="button" class="btn btn-warning btn-lg mt-4 fw-semibold glow-button" data-bs-toggle="modal" data-bs-target="#registrationProcessModal">
      <i class="bi bi-pencil-fill me-2"></i>Comment procéder pour m'inscrire
    </button>
  </div>
</section>

<!-- Modal for registration process -->
<div class="modal fade" id="registrationProcessModal" tabindex="-1" aria-labelledby="registrationProcessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registrationProcessModalLabel">Processus d'inscription à une formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-start">
        <ol>
          <li>Choisissez la formation qui vous intéresse dans notre catalogue.</li>
          <li>Créez un compte ou connectez-vous si vous en avez déjà un.</li>
          <li>Remplissez le formulaire d'inscription avec vos informations personnelles.</li>
          <li>Effectuez le paiement sécurisé via notre plateforme.</li>
          <li>Téléchargez votre reçu d'inscription après connexion à votre compte.</li>
          <li>Participez à la formation en présentiel selon le planning.</li>
        </ol>
        <p>Pour toute question, n'hésitez pas à nous contacter via la page Contact.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer  text-white pt-5 pb-3" style="background-color: #002e5d;">
  <div class="container">
    <div class="row">
      <!-- Colonne 1 -->
      <div class="col-md-4">
        <h5 class="text-warning mb-3">Betuole Académie</h5>
        <p>Centre de formation professionnelle innovant, ouvert à tous, accessible en ligne 24h/24.</p>
      </div>
      <!-- Colonne 2 -->
      <div class="col-md-4">
        <h5 class="text-warning mb-3">Liens rapides</h5>
        <ul class="list-unstyled">
          <li><a href="parent/admin/ges_formation/catalogue.php" class="text-white text-decoration-none">Nos formations</a></li>
          <li><a href="parent/auth/register.php" class="text-white text-decoration-none">S’inscrire</a></li>
          <li><a href="parent/auth/login.php" class="text-white text-decoration-none">Se connecter</a></li>
          <li><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
        </ul>
      </div>
      <!-- Colonne 3 -->
      <div class="col-md-4">
        <h5 class="text-warning mb-3">Contact</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i>Bépanda Maturité, Douala</p>
        <p><i class="bi bi-envelope-fill me-2"></i>contact@betuole-academie.com</p>
        <p><i class="bi bi-telephone-fill me-2"></i>+237 6 79 16 48 01</p>
      </div>
    </div>
    <hr class="bg-light">
    <p class="text-center mb-0">© <?php echo date('Y'); ?> Betuole Académie. Tous droits réservés.</p>
  </div>
</footer>

<script>
  window.addEventListener("scroll", function () {
    const nav = document.getElementById("mainNavbar");
    if (window.scrollY > 50) {
      nav.classList.add("navbar-scrolled");
    } else {
      nav.classList.remove("navbar-scrolled");
    }
  });

  // Color cycling for the h1 title
  (function() {
    const colors = ['#ff69b4', '#ffff00', '#87ceeb', '#f0f0f0', '#002e5d'];
    const title = document.getElementById('colorCycleTitle');
    let index = 0;
    setInterval(() => {
      title.style.color = colors[index];
      index = (index + 1) % colors.length;
    }, 1000); // change color every 1 second
  })();

  // Hide loading overlay when page fully loaded, but keep it visible at least 2000ms
  window.addEventListener('load', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
      setTimeout(() => {
        loadingOverlay.style.display = 'none';
      }, 2000);
    }
  });
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/typewriter.js"></script>
</body>
</html>
