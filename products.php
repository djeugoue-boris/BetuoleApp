<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bolio Store Cameroun | Boutique</title>
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
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link active" href="products.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Panier</a></li>
        <li class="nav-item"><a class="nav-link" href="social.php">Instagram & Réseaux</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5 page-top-space">
  <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h2 mb-1">Boutique Bolio</h1>
      <p class="text-muted mb-0">Recherche, filtres et articles réels pour le marché camerounais.</p>
    </div>
    <div class="search-wrap">
      <input id="searchInput" class="form-control" type="search" placeholder="Rechercher: polo, iPhone, chaussures...">
    </div>
  </div>

  <div class="row g-4" id="productGrid"></div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/store.js"></script>
<script>
  renderProductGrid('productGrid', 'searchInput');
</script>
</body>
</html>
