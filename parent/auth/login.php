<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion - Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Styles externes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      /* background: linear-gradient(135deg, #003366, #0059b3); */
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      margin: 0;
      color: #fff;
    }

    .navbar {
      background-color: #002244;
    }

    .navbar .nav-link {
      color: #fff;
      font-size: 1.1rem;
    }

    .navbar .nav-link:hover {
      color: #00bfff;
    }

    .form-box {
      background: #ffffff;
      color: #333;
      max-width: 600px;
      margin: 100px auto;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
    }

    .form-box h2 {
      font-size: 2rem;
      font-weight: bold;
      text-align: center;
      color: #003366;
      margin-bottom: 30px;
    }

    .form-control {
      height: 55px;
      font-size: 1.1rem;
    }

    .btn-primary {
      background-color: #0059b3;
      font-size: 1.1rem;
      padding: 12px;
      border: none;
      border-radius: 8px;
    }

    .btn-primary:hover {
      background-color: #0077e6;
    }

    .footer {
      background: #001a33;
      color: #ccc;
      padding: 40px 0;
      margin-top: 60px;
    }

    .footer h5 {
      color: #00bfff;
      margin-bottom: 15px;
    }

    .footer a {
      color: #ccc;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    } 
        /* style du formulaire */
        .login-section {
      position: relative;
      min-height: 100vh;
      background-image: url('../../img/salle01.jpg'); /* change cette image */
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .overlay-dark {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: linear-gradient(to bottom right, rgba(0, 30, 80, 0.7), rgba(0, 0, 0, 0.6));
      backdrop-filter: blur(6px);
      z-index: 1;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      max-width: 500px;
      z-index: 2;
      color: white;
      transition: 0.3s ease;
    }

    .form-floating i {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      color: #ccc;
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

  </style>
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
        <li><a href="../admin/ges_formations/catalogue.php" class="text-white">Formations</a></li>
      </ul>

      <!-- Bouton Créer un compte -->
      <!-- <a href="register.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
        <i class="bi bi-person-plus-fill me-1"></i> Créer un compte
      </a> -->
    </div>
  </div>
</nav>

<!-- FORMULAIRE -->
<section class="login-section">
  <div class="overlay-dark"></div>

  <div class="container position-relative z-3">
    <div class="login-card mx-auto p-4 p-md-5 rounded-4 shadow-lg">
      <h2 class="text-center text-white mb-4 fw-bold">Connexion à votre espace</h2>

      <?php if (!empty($_SESSION['error'])): ?>
        <script>
          Swal.fire({ icon: 'error', title: 'Erreur', text: '<?= $_SESSION["error"] ?>' });
        </script>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <script>
          Swal.fire({ icon: 'success', title: 'Succès', text: '<?= $_SESSION["success"] ?>' });
        </script>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <form method="POST" action="login_process.php">
        <div class="mb-4 form-floating position-relative">
          <input type="text" class="form-control bg-transparent text-white" id="matricule" name="matricule" placeholder="" required>
          <label for="matricule" class="text-white">Matricule</label>
          <i class="bi bi-person-badge-fill"></i>
        </div>

        <div class="mb-4 form-floating position-relative">
          <input type="email" class="form-control bg-transparent text-white" id="email" name="email" placeholder="" required>
          <label for="email" class="text-white">Adresse email</label>
          <i class="bi bi-envelope-fill"></i>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
          </button>
        </div>

        <div class="text-center mt-3">
          <a href="register.php" class="text-light text-decoration-underline">Pas encore inscrit ? Créez un compte</a>
        </div>
      </form>
    </div>
  </div>
</section>


<?php
if (isset($_SESSION['logout_success'])) {
    echo "
    <script>
      Swal.fire({
        icon: 'info',
        title: 'Déconnexion réussie',
        text: 'À bientôt sur Betuole Académie !',
        timer: 3000,
        showConfirmButton: false
      });
    </script>";
    unset($_SESSION['logout_success']);
}
?>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5>Betuole Académie</h5>
        <p>Formation moderne, en ligne et en présentiel, 100% adaptée à vos besoins.</p>
      </div>
      <div class="col-md-4">
        <h5>Navigation</h5>
        <ul class="list-unstyled">
          <li><a href="../admin/ges_formations/catalogue.php">Formations</a></li>
          <li><a href="inscription.php">S'inscrire</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="login.php">Connexion</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h5>Contact</h5>
        <p><i class="bi bi-geo-alt-fill"></i> Bépanda Maturité, Douala</p>
        <p><i class="bi bi-envelope-fill"></i> contact@betuole-academie.com</p>
        <p><i class="bi bi-telephone-fill"></i> +237 6 79 16 48 01</p>
      </div>
    </div>
    <hr>
    <p class="text-center">&copy; <?= date('Y') ?> Betuole Académie. Tous droits réservés.</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
