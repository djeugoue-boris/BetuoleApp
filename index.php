<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bolio Store Cameroun | Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/store.css">
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top navbar-dark glass-nav">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">BOLIO <span>STORE</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Panier</a></li>
        <li class="nav-item"><a class="nav-link" href="social.php">Instagram & Réseaux</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="hero-home d-flex align-items-center text-white">
  <div class="container text-center hero-content">
    <h1 class="display-4 fw-bold mb-3">Bolio Store Cameroun</h1>
    <p class="lead mb-4">Mode, téléphones, accessoires et tendances pour homme, femme et enfant.</p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a class="btn btn-warning btn-lg pulse-btn" href="products.php"><i class="bi bi-bag-heart-fill me-2"></i>Voir la boutique</a>
      <a class="btn btn-outline-light btn-lg" href="cart.php"><i class="bi bi-cart-check-fill me-2"></i>Commander maintenant</a>
    </div>
  </div>
</header>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="feature-card h-100">
          <i class="bi bi-stars fs-1 text-primary"></i>
          <h3 class="h5 mt-3">Style cool & animé</h3>
          <p>Un site vivant avec des boutons animés, transitions fluides et une expérience moderne.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card h-100">
          <i class="bi bi-phone-vibrate fs-1 text-primary"></i>
          <h3 class="h5 mt-3">Produits variés</h3>
          <p>T-shirts, chemises, polos, chapeaux, chaussures, smartphones et accessoires téléphone.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card h-100">
          <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
          <h3 class="h5 mt-3">Paiement avant confirmation</h3>
          <p>La commande n'est confirmée qu'après validation du paiement Mobile Money ou carte.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="py-4 text-white bg-dark">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <p class="mb-0">© <?php echo date('Y'); ?> Bolio Store Cameroun</p>
    <small>Livraison à Douala, Yaoundé, Bafoussam et partout au Cameroun.</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
