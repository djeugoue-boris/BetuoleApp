<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Instagram & Réseaux | Bolio Store</title>
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
        <li class="nav-item"><a class="nav-link" href="products.php">Boutique</a></li>
        <li class="nav-item"><a class="nav-link active" href="social.php">Instagram & Réseaux</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-5 page-top-space">
  <div class="text-center mb-5">
    <h1 class="h2">Retrouve Bolio Store partout</h1>
    <p class="text-muted">Commande, demande des nouveautés, et partage tes looks.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <a class="social-card insta" href="#" aria-label="Instagram">
        <i class="bi bi-instagram"></i>
        <h2>@bolio_store_cm</h2>
        <p>Nouveautés mode + vidéos produits en direct.</p>
      </a>
    </div>
    <div class="col-md-4">
      <a class="social-card whatsapp" href="#" aria-label="WhatsApp">
        <i class="bi bi-whatsapp"></i>
        <h2>WhatsApp Business</h2>
        <p>Conseil rapide et confirmation de livraison.</p>
      </a>
    </div>
    <div class="col-md-4">
      <a class="social-card tiktok" href="#" aria-label="TikTok">
        <i class="bi bi-tiktok"></i>
        <h2>TikTok Bolio</h2>
        <p>Try-on et unboxing des commandes clients.</p>
      </a>
    </div>
  </div>
</main>
</body>
</html>
