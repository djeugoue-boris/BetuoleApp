<?php
session_start();
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}



$formations = $pdo->query("SELECT id, nom, description, prix, quantite, image FROM formations")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Nos Formations | Betuole Académie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- new link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- sweetalert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
      color: #1a2e4a;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar */
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

    /* Main content */
    main {
      flex-grow: 1;
      max-width: 700px;
      margin: 6rem auto 3rem;
      padding: 2.5rem 2rem;
      background: white;
      border-radius: 8px;
      box-shadow: 0 3px 15px rgb(42 157 244 / 0.25);
    }
    main h1 {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #002e5d;
      text-align: center;
    }
    main p.lead {
      font-size: 1.1rem;
      margin-bottom: 2rem;
      color: #4a5d7a;
      text-align: center;
    }
    label {
      font-weight: 600;
      color: #2a9df4;
    }
    .form-control {
      border: 1.5px solid #a3bed8;
      border-radius: 5px;
      padding: 0.75rem 1rem;
      transition: border-color 0.3s ease;
    }
    .form-control:focus {
      border-color: #2a9df4;
      box-shadow: none;
      outline: none;
    }
    .form-text {
      font-size: 0.85rem;
      color: #6c7a93;
    }

    button[type="submit"] {
      background-color: #2a9df4;
      border: none;
      color: white;
      padding: 0.75rem 1rem;
      font-weight: 600;
      border-radius: 5px;
      width: 100%;
      transition: background-color 0.3s ease;
    }
    button[type="submit"]:hover {
      background-color: #1d78d6;
    }

    /* Footer */
    footer {
      background-color: #002e5d;
      color: #cbd5e1;
      text-align: center;
      padding: 1.25rem 1rem;
      font-weight: 500;
      font-size: 0.9rem;
      flex-shrink: 0;
      user-select: none;
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

    /* Responsive */
    @media (max-width: 576px) {
      main {
        margin: 5rem 1rem 2rem;
        padding: 2rem 1rem;
      }
    }
  </style>
</head>
<body>

 <!-- ===== NAVBAR MODERNE ===== -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-navbar shadow-sm py-3">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand fw-bold text-white" href="#">
    <img src="../../../img/logo.jpg" alt="Logo" class="rounded-2 me-2" style="height: 40px;"> Betuole Académie
    </a>

    <!-- Burger toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="../../../index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="../../../apropos.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="../../../actualites.php">Actualités</a></li>
        <li class="nav-item"><a class="nav-link" href="../../../contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link active" href="#">Formations</a></li>
      </ul>

      <!-- Bouton Créer un compte -->
      <?php if (isset($_SESSION['user_id'])): ?>
         <a href="../../apprenant/dashboard_app.php" class="nav-link text-light text-decoration-none me-2">Dashboard user</a>
        <a href="../../auth/logout.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
          <i class="bi bi-box-arrow-right me-1"></i> Se déconnecter
        </a>
      <?php else: ?>
        <a href="../../auth/register.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
          <i class="bi bi-person-plus-fill me-1"></i> Créer un compte
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<br><br><br>
<style>
  .formation-card {
    border: none;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all .3s ease;
    background-color: #fff;
    position: relative;
  }

  .formation-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
  }

  .formation-card .cover {
    position: relative;
    height: 200px;
    background-size: cover;
    background-position: center;
    transition: transform .3s ease;
  }

  .formation-card:hover .cover {
    transform: scale(1.05);
  }

  .formation-card .overlay-gradient {
    position: absolute;
    bottom: 0;
    left: 0; right: 0;
    height: 60%;
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
  }

  .badge-price, .badge-places {
    position: absolute;
    top: 1rem;
    background: #ffc107;
    color: #000;
    font-weight: bold;
    padding: .3rem .8rem;
    border-radius: 1rem;
    font-size: 0.85rem;
    animation: pop .4s ease-out;
  }

  .badge-places {
    right: 1rem;
    background: #0dcaf0;
  }

  .badge-price {
    left: 1rem;
  }

  @keyframes pop {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }

  .formation-card .card-body {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    min-height: 180px;
  }

  .formation-card h5 {
    font-weight: 600;
    margin-bottom: .5rem;
    color: #333;
  }

  .formation-card .card-text {
    flex-grow: 1;
    color: #666;
    font-size: 0.95rem;
  }

  .btn-inscription {
    background-color: #6610f2;
    color: #fff;
    border-radius: 2rem;
    font-weight: bold;
    transition: all 0.3s ease;
  }

  .btn-inscription:hover {
    background-color: #520dc2;
  }
</style>

<div class="container py-5">
  <h2 class="text-center fw-bold mb-5">📚 Nos Formations Disponibles</h2>
  <div class="row g-4">
    <?php foreach($formations as $f): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card formation-card h-100">
        <div class="cover" style="background-image: url('<?= htmlspecialchars($f['image']) ?>');">
          <div class="overlay-gradient"></div>
          <div class="badge-price"><?= number_format($f['prix'],0,',',' ') ?> FCFA</div>
          <div class="badge-places"><?= $f['quantite'] ?> places</div>
        </div>
        <div class="card-body">
          <h5><?= htmlspecialchars($f['nom']) ?></h5>
          <p class="card-text"><?= htmlspecialchars(mb_strimwidth($f['description'], 0, 100, '…')) ?></p>
          <a href="detail.php?id=<?= $f['id'] ?>" class="btn btn-inscription mt-3 w-100">
            Voir les détails
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>





</body>
</html>
