<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panier & Commande | Bolio Store</title>
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
        <li class="nav-item"><a class="nav-link active" href="cart.php">Panier</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5 page-top-space">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h1 class="h3 mb-4">Mon panier</h1>
          <div id="cartItems"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h2 class="h5 mb-3">Résumé commande</h2>
          <p class="d-flex justify-content-between"><span>Sous-total</span><strong id="subTotal">0 FCFA</strong></p>
          <p class="d-flex justify-content-between"><span>Livraison</span><strong>2 500 FCFA</strong></p>
          <hr>
          <p class="d-flex justify-content-between"><span>Total</span><strong id="grandTotal">0 FCFA</strong></p>
          <small class="text-muted d-block mb-3">La commande sera confirmée uniquement après un paiement validé.</small>

          <label class="form-label">Mode de paiement</label>
          <select id="paymentMethod" class="form-select mb-3">
            <option value="">Choisir un moyen de paiement</option>
            <option>Mobile Money (MTN / Orange)</option>
            <option>Carte bancaire</option>
          </select>

          <button id="payBtn" class="btn btn-success w-100 mb-2"><i class="bi bi-credit-card-fill me-2"></i>Payer maintenant</button>
          <button id="confirmOrderBtn" class="btn btn-dark w-100" disabled><i class="bi bi-patch-check-fill me-2"></i>Confirmer la commande</button>
          <div id="orderMessage" class="mt-3"></div>
        </div>
      </div>

      <div class="assistant-box position-relative overflow-hidden">
        <div class="assistant-walker">😎</div>
        <h3 class="h6">Coach paiement</h3>
        <p class="mb-0">Salut 👋 Je t'accompagne ! Choisis un moyen, paie puis confirme ta commande.</p>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/store.js"></script>
<script>
  renderCart();
</script>
</body>
</html>
