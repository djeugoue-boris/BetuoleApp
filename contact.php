<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Contact | Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Contactez Betuole Académie pour toute information ou suggestion.">
  <meta name="author" content="Betuole Dev Team">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">

  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
      color: #1a2e4a;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background-color: #002e5d;
      box-shadow: 0 2px 6px rgb(0 46 92 / 0.4);
      padding: 0.8rem 1rem;
    }
    .navbar-brand, .navbar-nav .nav-link {
      color: #cbd5e1;
      font-weight: 600;
      transition: color 0.25s ease;
    }
    .navbar-brand:hover, .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #2a9df4;
      text-decoration: none;
    }
    .btn-outline-light {
      border: 1.5px solid #2a9df4;
      color: #2a9df4;
      font-weight: 600;
      padding: 0.45rem 1.2rem;
      border-radius: 0.3rem;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-outline-light:hover {
      background-color: #2a9df4;
      color: white;
    }

    main {
      flex-grow: 1;
      max-width: 700px;
      margin: 6rem auto 3rem;
      padding: 2.5rem 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgb(42 157 244 / 0.3);
    }
    main:hover {
      box-shadow: 0 8px 30px rgb(42 157 244 / 0.4);
    }
    main h1 {
      font-weight: 800;
      font-size: 2.25rem;
      margin-bottom: 1.5rem;
      color: #002e5d;
      text-align: center;
    }
    main p.lead {
      font-size: 1.15rem;
      margin-bottom: 2.5rem;
      color: #4a5d7a;
      text-align: center;
    }

    label {
      font-weight: 700;
      color: #2a9df4;
    }
    .form-control {
      border: 2px solid #a3bed8;
      border-radius: 8px;
      padding: 0.85rem 1.2rem;
      font-size: 1rem;
      font-weight: 500;
      color: #1a2e4a;
      background-color: #f9fbfd;
    }
    .form-control:focus {
      border-color: #2a9df4;
      box-shadow: 0 0 10px 3px rgba(42, 157, 244, 0.6);
      outline: none;
      background-color: white;
    }
    .form-text {
      font-size: 0.9rem;
      color: #5a6a85;
    }
    button[type="submit"] {
      background: linear-gradient(135deg, #2a9df4, #1d78d6);
      border: none;
      color: white;
      padding: 0.85rem 1.2rem;
      font-weight: 700;
      border-radius: 8px;
      width: 100%;
      font-size: 1.1rem;
      letter-spacing: 0.8px;
    }
    button[type="submit"]:hover {
      background: linear-gradient(135deg, #1d78d6, #2a9df4);
      box-shadow: 0 6px 18px rgba(29, 120, 214, 0.8);
      cursor: pointer;
    }

    footer {
      background-color: #002e5d;
      color: #cbd5e1;
      text-align: center;
      padding: 1.25rem 1rem;
      font-weight: 500;
      font-size: 0.9rem;
      flex-shrink: 0;
    }
    footer a {
      color: #a3bed8;
      text-decoration: none;
      font-weight: 600;
    }
    footer a:hover {
      color: #2a9df4;
      text-decoration: underline;
    }

    @media (max-width: 576px) {
      main {
        margin: 5rem 1rem 2rem;
        padding: 2rem 1rem;
      }
        }
    .custom-input {
      border-radius: 8px;
      padding: 0.5rem 0.8rem; /* moins de padding */
      border: 1.5px solid #d0dfea;
      background-color: #f8fafd;
      font-size: 0.9rem; /* texte un peu plus petit */
      transition: all 0.3s ease;
    }
    .custom-input:focus {
      border-color: #1d78d6;
      box-shadow: 0 0 0 0.15rem rgba(29, 120, 214, 0.2);
      background-color: #fff;
    }

    .btn-gradient {
      background: linear-gradient(90deg, #1d78d6, #2a9df4);
      color: white;
      border: none;
      border-radius: 30px;
      font-size: 1rem; /* un peu plus petit */
      padding: 0.5rem 0; /* moins de padding vertical */
      font-weight: 600;
      transition: all 0.3s ease-in-out;
    }
    .btn-gradient:hover {
      background: linear-gradient(90deg, #1861aa, #2489c9);
      transform: scale(1.02);
    }

    .card {
      border-radius: 1.5rem;
      padding: 1.5rem 1.5rem !important; /* un peu moins de padding */
    }

    h2.fw-bold {
      font-size: 1.7rem !important; /* un peu plus petit */
    }

    p.text-muted {
      font-size: 0.85rem !important;
    }

    @media (max-width: 768px) {
      .row.g-3 > div {
        margin-bottom: 1.3rem;
      }
    }
  </style>
</head>
<body>

<!-- ===== NAVBAR MODERNE ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-navbar shadow-sm py-3">
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
        <li class="nav-item"><a class="nav-link" href="actualites.php">Actualités</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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

</nav><br>
<main class="container py-5" style="margin-top: 6rem;">
  <div class="card border-0 shadow rounded-4 p-4 p-md-5 bg-white">
    <div class="row g-4">
      <!-- Colonne de gauche -->
      <div class="col-md-6">
       <form id="contactForm" action="" method="post">
          <h2 class="fw-bold text-primary mb-3">Envoyez-nous un message</h2>
          <p class="text-muted mb-4">Vous avez une question, une préoccupation ou besoin d'information ? Nous sommes là pour vous répondre rapidement.</p>

          <div class="mb-3">
            <label for="nom" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
            <input type="text" class="form-control custom-input" id="nom" name="nom" placeholder="Jean Dupont" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Adresse email <span class="text-danger">*</span></label>
            <input type="email" class="form-control custom-input" id="email" name="email" placeholder="exemple@mail.com" required>
          </div>

          <div class="mb-3">
            <label for="telephone" class="form-label fw-semibold">Téléphone</label>
            <input type="tel" class="form-control custom-input" id="telephone" name="telephone" placeholder="+237 6 XX XX XX">
          </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-6">
          <div class="mb-3">
            <label for="objet" class="form-label fw-semibold">Objet <span class="text-danger">*</span></label>
            <input type="text" class="form-control custom-input" id="objet" name="objet" placeholder="Sujet de votre message" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
            <textarea class="form-control custom-input" id="message" name="message" rows="8" placeholder="Tapez votre message ici..." required></textarea>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-gradient fw-bold py-2">
              <i class="bi bi-send-fill me-1"></i> Envoyer maintenant
            </button>
          </div>
       </form>
      </div>
    </div>
  </div>
</main>



<!-- FOOTER -->
<footer>
  <div>© <?php echo date('Y'); ?> Betuole Académie. Tous droits réservés.</div>
</footer>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  (function() {
    'use strict';

    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();

      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      const data = {
        nom: $('#nom').val(),
        email: $('#email').val(),
        objet: $('#objet').val(),
        telephone: $('#telephone').val(),
        message: $('#message').val()
      };

      $('button[type="submit"]').prop('disabled', true);

      $.ajax({
        url: 'parent/admin/ges_contacts/index.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
          $('button[type="submit"]').prop('disabled', false);
          if(response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Message envoyé !',
              text: response.message,
              confirmButtonColor: '#3085d6'
            }).then(() => {
              form.reset();
              form.classList.remove('was-validated');
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Erreur',
              text: response.message,
              confirmButtonColor: '#d33'
            });
          }
        },
        error: function() {
          $('button[type="submit"]').prop('disabled', false);
          Swal.fire({
            icon: 'error',
            title: 'Erreur serveur',
            text: 'Une erreur est survenue. Veuillez réessayer plus tard.',
            confirmButtonColor: '#d33'
          });
        }
      });
    });
  })();
</script>
</body>
</html>
