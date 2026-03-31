<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Détail produit | Bolio Store</title>
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
        <li class="nav-item"><a class="nav-link" href="products.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Panier</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5 page-top-space" id="productDetailPage"></main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/store.js"></script>
<script>
  renderProductDetail('productDetailPage');
</script>
</body>
</html>
