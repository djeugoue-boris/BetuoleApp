<?php
session_start(); 


require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


if(!isset($_GET['id'])) {
  header('Location: catalogue.php'); exit;
}
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM formations WHERE id=?");
$stmt->execute([$id]);
$f = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$f) {
  echo "Formation introuvable"; exit;
}
?>
<?php
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
    <!-- sweetalert2 -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($f['nom']) ?></title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <li class="nav-item"><a class="nav-link" href="../../../contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="catalogue.php">Formations</a></li>
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


    <?php if (isset($_GET['success']) && $_GET['success'] === 'paiement'): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Paiement initié',
        text: 'Veuillez suivre les instructions sur votre téléphone.',
        timer: 3000
    });
    </script>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Échec du paiement',
        text: 'Veuillez réessayer.',
    });
    </script>
    <?php endif; ?>
<style>
  .formation-detail {
    max-width: 1200px;
    margin: auto;
    padding: 40px 15px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .formation-header {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.07);
    overflow: hidden;
    padding: 30px;
    align-items: center;
  }

  .formation-image {
    flex: 1 1 45%;
    border-radius: 12px;
    overflow: hidden;
  }

  .formation-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
    transition: transform 0.3s ease-in-out;
    border-radius: 12px;
  }

  .formation-image img:hover {
    transform: scale(1.02);
  }

  .formation-content {
    flex: 1 1 50%;
  }

  .formation-title {
    font-size: 2rem;
    font-weight: bold;
    color: #222;
    margin-bottom: 15px;
  }

  .formation-description {
    color: #555;
    line-height: 1.7;
    margin-bottom: 20px;
    font-size: 1rem;
  }

  .formation-meta {
    margin-bottom: 15px;
    font-size: 1rem;
    color: #333;
  }

  .formation-meta i {
    color: #28a745;
    margin-right: 8px;
  }

  .btn-pay {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 25px;
    font-size: 1rem;
    border-radius: 8px;
    transition: background 0.3s ease;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
  }

  .btn-pay:hover {
    background: #218838;
  }

  .back-link {
    margin-bottom: 25px;
    display: inline-block;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .back-link:hover {
    color: #28a745;
  }
</style>

<div class="formation-detail">
  <a href="catalogue.php" class="back-link">&larr; Revenir au catalogue</a>

  <div class="formation-header">

    <!-- IMAGE -->
    <div class="formation-image">
      <?php if($f['image']): ?>
        <img src="<?= htmlspecialchars($f['image']) ?>" alt="Image de la formation">
      <?php endif; ?>
    </div>

    <!-- CONTENU -->
    <div class="formation-content">
      <h1 class="formation-title"><?= htmlspecialchars($f['nom']) ?></h1>

      <p class="formation-description"><?= nl2br(htmlspecialchars($f['description'])) ?></p>

      <div class="formation-meta">
        <i class="fas fa-users"></i>
        <strong>Places disponibles :</strong> <?= $f['quantite'] ?>
      </div>

      <div class="formation-meta">
        <i class="fas fa-money-bill-wave"></i>
        <strong>Prix :</strong> <?= number_format($f['prix'],2,',',' ') ?> FCFA
      </div>

      <a href="../../auth/login.php" class="btn btn-pay mt-3">
        <i class="fas fa-sign-in-alt me-1"></i> Se connecter pour s'inscrire
      </a>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
