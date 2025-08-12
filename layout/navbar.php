<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Calculate relative path prefix from current script to project root
function getRelativePathPrefix() {
    $currentPath = dirname($_SERVER['SCRIPT_NAME']);
    $depth = substr_count(trim($currentPath, '/'), '/');
    if ($depth === 0) {
        return './';
    }
    return str_repeat('../', $depth + 1);
}

$prefix = getRelativePathPrefix();
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-navbar shadow-sm py-3" style="background-color: #002e5d; backdrop-filter: blur(10px);">
  <div class="container">
    <a class="navbar-brand fw-bold text-white" href="<?php echo $prefix; ?>index.php">
      <img src="<?php echo $prefix; ?>img/logo.jpg" alt="Logo" class="rounded-2 me-2" style="height: 40px;">
      Betuole Académie
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="<?php echo $prefix; ?>index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $prefix; ?>apropos.php">A propos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $prefix; ?>actualites.php">Actualites</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $prefix; ?>contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $prefix; ?>parent/admin/ges_formations/catalogue.php">Formations</a></li>
      </ul>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo $prefix; ?>parent/auth/logout.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
          <i class="bi bi-box-arrow-right me-1"></i> Se déconnecter
        </a>
      <?php else: ?>
        <a href="<?php echo $prefix; ?>parent/auth/register.php" class="btn btn-outline-light ms-lg-4 mt-3 mt-lg-0 fw-semibold">
          <i class="bi bi-person-plus-fill me-1"></i> Créer un compte
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
