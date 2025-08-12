<?php
session_start();
require_once '../../config.php';

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
require_once '../../config.php';

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
      <i class="bi bi-mortarboard-fill me-2"></i> Betuole Académie
    </a>

    <!-- Burger toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="../../index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="../../apropos.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="../../contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Formations</a></li>
      </ul>

      <!-- Bouton Créer un compte -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="../auth/logout.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
          <i class="bi bi-box-arrow-right me-1"></i> Se déconnecter
        </a>
      <?php else: ?>
        <a href="../auth/register.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
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

  <div class="container py-5">
    <a href="catalogue.php" class="btn btn-link mb-4">&larr; Revenir au catalogue</a>
    <div class="row">
      <div class="col-md-6">
        <?php if($f['image']): ?>
          <img src="../admin/ges_formations<?= htmlspecialchars($f['image']) ?>" alt="image" class="img-fluid rounded">
        <?php endif; ?>
      </div>
      <div class="col-md-6">
        <h2><?= htmlspecialchars($f['nom']) ?></h2>
        <p><?= nl2br(htmlspecialchars($f['description'])) ?></p>
        <p><strong>Places disponibles :</strong> <?= $f['quantite'] ?></p>
        <p><strong>Prix :</strong> <?= number_format($f['prix'],2,',',' ') ?> FCFA</p>

        <!-- Bouton déclencheur du modal de paiement -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payModal">
          S'inscrire / Payer
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Paiement -->
  <div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="payForm" class="modal-content" method="POST" action="process_payment.php">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Payer la formation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_formation" value="<?= $f['id'] ?>">
          <div class="mb-3">
            <label>Mode de paiementttttt</label>
            <select name="mode" class="form-select" required>
              <option value="">-- Choisir --</option>
              <option value="MTN">MTN Mobile Money</option>
              <option value="Orange">Orange Money</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Montant (FCFA)</label>
            <input type="number" name="montant" class="form-control" 
                   value="<?= htmlspecialchars($f['prix']) ?>" min="1" required>
          </div>
          <div class="mb-3">
            <label>Numéro Mobile</label>
            <input type="tel" name="tel" class="form-control" placeholder="+2376XXXXXXX" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Payer avec Cinpay</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
