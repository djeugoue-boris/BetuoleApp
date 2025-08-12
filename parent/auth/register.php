ment la uton radiolem<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
  <head>
  <meta charset="UTF-8" />
  <title>Inscription - Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4fb;
    }

    .navbar {
      background-color: #003366;
    }

    .navbar-brand,
    .nav-link {
      color: #fff !important;
    }

    .nav-link:hover {
      color: #00bfff !important;
    }

    .container-form {
      max-width: 700px;
      margin: 100px auto 60px;
      background: #fff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .form-floating i {
      position: absolute;
      top: 18px;
      right: 20px;
      color: #007bff;
    }

    footer {
      background: #111;
      color: #ccc;
      padding: 40px 0 20px;
    }

        /* formulaire  */
    .register-section {
      position: relative;
      min-height: 100vh;
      background-image: url('../../img/salle01.jpg'); /* ➕ ton image */
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-top: 120px;
      padding-bottom: 80px;
    }

    .overlay-dark {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: linear-gradient(to bottom right, rgba(15, 21, 45, 0.71), rgba(15, 48, 82, 0.48));
      backdrop-filter: blur(6px);
      z-index: 1;
    }

    .register-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      max-width: 600px;
      z-index: 2;
      color: white;
      transition: 0.3s ease;
    }

    .form-floating i {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      color: #ddd;
      font-size: 1.2rem;
      pointer-events: none;
    }

    .form-control,
    .form-select {
      background: transparent;
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
    }

    .form-control:focus,
    .form-select:focus {
      box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
      border-color: #66afe9;
      background: rgba(255, 255, 255, 0.1);
    }

    ::placeholder {
      color: #ccc;
    }


  </style>
  <script>
    $(document).ready(function() {
      flatpickr("#date_naissance", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        altInput: true,
        altFormat: "d-m-Y",
        allowInput: true
      });
    });
  </script>
</head>
<body>

<!-- ===== NAVBAR MODERNE ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-navbar shadow-sm py-3">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand fw-bold text-white" href="#">
    <img src="../../img/logo.jpg" alt="Logo" class="rounded-2 me-2" style="height: 40px;"> Betuole Académie
    </a>

    <!-- Burger toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
      <li class="nav-item"><a class="nav-link" href="../../index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="../../apropos.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="../../actualites.php">Actualites</a></li>
        <li class="nav-item"><a class="nav-link" href="../../contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="../admin/ges_formations/catalogue.php">Formations</a></li>
      </ul>

      <!-- Bouton Créer un compte -->
      <!-- <a href="register.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
        <i class="bi bi-person-plus-fill me-1"></i> Créer un compte
      </a> -->
    </div>
  </div>
</nav>

<!-- FORMULAIRE -->
<section class="register-section">
  <div class="overlay-dark"></div>

  <div class="container position-relative z-3">
    <div class="register-card mx-auto p-4 p-md-5 rounded-4 shadow-lg">
      <h3 class="text-center text-white mb-4 fw-bold">Créer un compte</h3>

      <!-- Affichage des messages d'erreur ou de succès -->
      <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger mt-3" role="alert">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success mt-3" role="alert">
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="register_process.php">
        <div class="row g-3">
          <div class="col-md-6 form-floating position-relative">
            <input type="text" class="form-control bg-transparent text-white" id="nom" name="nom" placeholder="Nom" required>
            <label for="nom" class="text-white">Nom</label>
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="col-md-6 form-floating position-relative">
            <input type="text" class="form-control bg-transparent text-white" id="prenom" name="prenom" placeholder="Prénom" required>
            <label for="prenom" class="text-white">Prénom</label>
            <i class="bi bi-person-lines-fill"></i>
          </div>
          <div class="col-md-6 position-relative mb-3">
            <label class="text-white d-block mb-2">Sexe</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sexe" id="sexeM" value="M" required>
              <label class="form-check-label text-white" for="sexeM">Masculin</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sexe" id="sexeF" value="F" required>
              <label class="form-check-label text-white" for="sexeF">Féminin</label>
            </div>
            <i class="bi bi-gender-ambiguous" style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); color: #ddd; font-size: 1.2rem; pointer-events: none;"></i>
          </div>
          <div class="col-md-6 form-floating position-relative mb-3">
            <input type="date" class="form-control bg-transparent text-white" id="date_naissance" name="date_naissance" required>
            <label for="date_naissance" class="text-white">Date de naissance</label>
            <i class="bi bi-calendar-date"></i>
          </div>
          <div class="col-12 form-floating position-relative">
            <input type="text" class="form-control bg-transparent text-white" id="cni" name="cni" placeholder="Numéro de CNI" required>
            <label for="cni" class="text-white">Numéro de CNI</label>
            <i class="bi bi-credit-card-2-front"></i>
ton           </div>
          <div class="col-md-6 form-floating position-relative">
            <input type="email" class="form-control bg-transparent text-white" id="email" name="email" placeholder="Email" required>
            <label for="email" class="text-white">Email</label>
            <i class="bi bi-envelope-fill"></i>
          </div>
          <div class="col-md-6 form-floating position-relative">
            <input type="tel" class="form-control bg-transparent text-white" id="telephone" name="telephone" placeholder="Téléphone" required>
            <label for="telephone" class="text-white">Téléphone</label>
            <i class="bi bi-telephone-fill"></i>
          </div>
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary btn-lg fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i> S'inscrire
          </button>
        </div>

        <div class="text-center mt-3">
          <a href="login.php" class="text-light text-decoration-underline">Déjà inscrit ? Connectez-vous</a>
        </div>
      </form>
    </div>
  </div>
</section>


<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5 class="text-primary">Betuole Académie</h5>
        <p>Centre de formation professionnelle moderne, accessible 24h/24.</p>
      </div>
      <div class="col-md-4">
        <h5 class="text-primary">Liens utiles</h5>
        <ul class="list-unstyled">
          <li><a href="../admin/ges_formations/catalogue.php" class="text-white">Formations</a></li>
          <li><a href="inscription.php" class="text-white">S'inscrire</a></li>
          <li><a href="login.php" class="text-white">Connexion</a></li>
          <li><a href="contact.php" class="text-white">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5 class="text-primary">Contact</h5>
        <p><i class="bi bi-geo-alt-fill me-2"></i>Bépanda Maturité, Douala</p>
        <p><i class="bi bi-envelope-fill me-2"></i>contact@betuole-academie.com</p>
        <p><i class="bi bi-telephone-fill me-2"></i>+237 6 79 16 48 01</p>
      </div>
    </div>
    <hr class="bg-light">
    <p class="text-center mb-0">© <?php echo date('Y'); ?> Betuole Académie. Tous droits réservés.</p>
  </div>
</footer>

</body>
</html>
