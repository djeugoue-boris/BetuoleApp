<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Stats globales
$stats = [
    'admins' => $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'")->fetchColumn(),
    'apprenants' => $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'apprenant'")->fetchColumn(),
    'formations' => $pdo->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
    'paiements' => $pdo->query("SELECT COUNT(*) FROM paiements")->fetchColumn(),
    'actualites' => $pdo->query("SELECT COUNT(*) FROM actualites")->fetchColumn(),
    'annee_active' => $pdo->query("SELECT annee FROM annees WHERE statut = 'active' LIMIT 1")->fetchColumn() ?: 'Aucune',
    'archives' => $pdo->query("SELECT COUNT(*) FROM archives")->fetchColumn(),
    'annees_archivees' => $pdo->query("SELECT COUNT(*) FROM annees WHERE statut = 'archive'")->fetchColumn(),
];

// Préparation données pour graphiques (exemple : évolution des inscriptions par mois sur 6 mois)
$mois_labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
$inscriptions_data = [12, 30, 25, 40, 50, 60]; // Exemple stat, à récupérer depuis ta base selon besoins

?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gestion des Actualités</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
<style>
  /* Copier tout le CSS de admins.php ici pour uniformiser */
  /* Reset & base */
  body, html {
    margin: 0; padding: 0; height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg);
    color: var(--text);
    transition: background-color 0.4s, color 0.4s;
  }

  :root {
    --bg: #f8f9fa;
    --text: #212529;
    --primary: #0d6efd;
    --primary-dark: #084298;
    --success: #198754;
    --warning: #ffc107;
    --danger: #dc3545;
    --sidebar-bg: #212529;
    --sidebar-text: #f8f9fa;
    --sidebar-hover: #343a40;
  }
  [data-theme="dark"] {
    --bg: #121212;
    --text: #e9ecef;
    --primary: #0d6efd;
    --primary-dark: #084298;
    --success: #198754;
    --warning: #ffc107;
    --danger: #dc3545;
    --sidebar-bg: #1b1b1b;
    --sidebar-text: #f8f9fa;
    --sidebar-hover: #2a2a2a;
  }

  /* Sidebar */
  nav.sidebar {
    position: fixed;
    top: 0; bottom: 0; left: 0;
    width: 250px;
    background-color: var(--sidebar-bg);
    color: var(--sidebar-text);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    box-shadow: 2px 0 8px rgba(0,0,0,0.4);
    transition: background-color 0.4s;
    z-index: 1100;
  }
  nav.sidebar::-webkit-scrollbar {
    width: 8px;
  }
  nav.sidebar::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 4px;
  }
  nav.sidebar h3 {
    margin: 1rem 1.5rem;
    font-weight: 700;
    font-size: 1.8rem;
    letter-spacing: 2px;
  }
  nav.sidebar a {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 15px 1.8rem;
    font-size: 1.1rem;
    color: var(--sidebar-text);
    text-decoration: none;
    transition: background-color 0.3s;
    border-left: 4px solid transparent;
  }
  nav.sidebar a:hover, nav.sidebar a.active {
    background-color: var(--sidebar-hover);
    border-left: 4px solid var(--primary);
    color: var(--primary);
  }
  nav.sidebar a i {
    font-size: 1.3rem;
    width: 28px;
    text-align: center;
  }
  nav.sidebar .logout-btn {
    margin-top: auto;
    margin-bottom: 1.5rem;
    margin-left: 1.5rem;
    margin-right: 1.5rem;
  }

  /* Topbar */
  header.topbar {
    position: fixed;
    top: 0; left: 250px; right: 0;
    height: 60px;
    background-color: var(--sidebar-bg);
    color: var(--sidebar-text);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 1.8rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: background-color 0.4s;
    z-index: 1050;
  }
  header.topbar button#themeToggleBtn {
    background: none;
    border: none;
    color: inherit;
    font-size: 1.8rem;
    cursor: pointer;
    transition: color 0.3s;
  }
  header.topbar button#themeToggleBtn:hover {
    color: var(--primary);
  }

  /* Main content */
  main.content {
    margin-left: 250px;
    margin-top: 60px;
    padding: 2.5rem 3rem;
    min-height: calc(100vh - 60px);
    overflow-y: auto;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
    gap: 1.7rem;
  }
  .stat-card {
    background: var(--primary);
    color: white;
    border-radius: 15px;
    padding: 2rem 1.5rem;
    box-shadow: 0 10px 20px rgb(13 110 253 / 0.3);
    display: flex;
    flex-direction: column;
    justify-content: center;
    user-select: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgb(13 110 253 / 0.6);
  }
  .stat-card h4 {
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .stat-card p.value {
    font-size: 3.5rem;
    font-weight: 900;
    margin: 0;
    letter-spacing: 2px;
  }
  /* Couleurs spécifiques */
  .stat-admin { background: linear-gradient(135deg,#0d6efd,#084298); }
  .stat-apprenant { background: linear-gradient(135deg,#198754,#146c43); }
  .stat-formation { background: linear-gradient(135deg,#ffc107,#cc9a06); color: #222; box-shadow: 0 10px 20px rgb(255 193 7 / 0.3);}
  /* .stat-paiement { background: linear-gradient(135deg,#6f42c1,#4e3283); } */
  .stat-actualite { background: linear-gradient(135deg,#20c997,#0e766e); }
  .stat-annee-active { background: linear-gradient(135deg,#fd7e14,#b36000); }
  .stat-archive { background: linear-gradient(135deg,#e0a800,#9d7e00); color: #222;}
  .stat-annees-archivees { background: linear-gradient(135deg,#5a3298,#3e2369); }

  /* Responsive */
  @media (max-width: 992px) {
    nav.sidebar {
      width: 220px;
    }
    main.content {
      margin-left: 220px;
      padding: 2rem 2.5rem;
    }
    header.topbar {
      left: 220px;
    }
  }
  @media (max-width: 768px) {
    nav.sidebar {
      width: 60px;
    }
    nav.sidebar a span {
      display: none;
    }
    nav.sidebar h3 {
      display: none;
    }
    main.content {
      margin-left: 60px;
      padding: 1.5rem 1.5rem;
    }
    header.topbar {
      left: 60px;
      padding: 0 1rem;
    }
  }
  @media (max-width: 480px) {
    nav.sidebar {
      position: relative;
      width: 100%;
      height: auto;
      flex-direction: row;
      overflow-x: auto;
      box-shadow: none;
    }
    nav.sidebar a {
      padding: 0.75rem 1rem;
      font-size: 0.9rem;
      border-left: none !important;
      justify-content: center;
    }
    nav.sidebar a i {
      margin-right: 0;
      width: auto;
    }
    main.content {
      margin-left: 0;
      margin-top: 120px;
      padding: 1rem 1rem;
    }
    header.topbar {
      position: fixed;
      left: 0;
      top: 60px;
      right: 0;
      height: 50px;
      padding: 0 1rem;
    }
  }

  /* Graph container */
  #chartsTabs {
    margin-top: 3rem;
  }
  .nav-tabs .nav-link {
    cursor: pointer;
  }
  .tab-content {
    margin-top: 2rem;
  }
      body {
      background: var(--bg) !important;
      color: var(--text) !important;
    }

    .table img {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
    }

    .action-btns i {
      font-size: 1.1rem;
    }

    .header-bar {
      background: linear-gradient(90deg, #0051a3, #007bff);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: .5rem .5rem 0 0;
    }

    .container-box {
      background: var(--bg) !important;
      color: var(--text) !important;
    }
    .logo-container {
      margin: 20px;
      padding: 10px;
    }

    .logo-container .logo {
      width: 100%;
      height: 100%;
      border-radius: 5%;
      object-fit: cover;
    }

    .brand-name {
      font-size: 20px;
      margin: 0;
      font-weight: bold;
    }

    .admin-panel {
      font-size: 14px;
      color: #ccc;
      margin: 0;
    }
    .admin-panel {
      font-size: 30px;
      color: #ccc;
      margin: 0;
    }

    /* Style DataTables adapté au thème */
    table.dataTable {
      background: var(--bg);
      color: var(--text);
      border-radius: 8px;
      overflow: hidden;
    }
    table.dataTable thead {
      background: linear-gradient(90deg, var(--primary-dark), var(--primary));
      color: #fff;
    }
    table.dataTable tbody tr:hover {
      background: var(--primary); /* couleur principale thème */
      color: #fff;
    }
    .dt-button, .buttons-html5, .buttons-print {
      background: var(--danger) !important;
      color: #fff !important;
      border: none !important;
      border-radius: 4px !important;
      margin-right: 4px;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: background 0.2s;
    }
    .dt-button:hover, .buttons-html5:hover, .buttons-print:hover {
      background: #ff3333 !important;
      color: #fff !important;
    }
    .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
      color: var(--text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      background: var(--sidebar-bg) !important;
      color: var(--sidebar-text) !important;
      border-radius: 3px;
      margin: 0 2px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: var(--danger) !important;
      color: #fff !important;
    }
  body {
    background: var(--bg) !important;
    color: var(--text) !important;
  }
  .container-box {
    background: var(--bg) !important;
    color: var(--text) !important;
  }
  .header-bar, .table-primary, .bg-primary, .modal-header.bg-primary {
    background: linear-gradient(90deg, var(--primary-dark), var(--primary)) !important;
    color: #fff !important;
  }
  .modal-header.bg-warning {
    background: var(--warning) !important;
    color: #222 !important;
  }
  .table, .table-bordered, .table-hover, .align-middle, .table th, .table td {
    background: var(--bg) !important;
    color: var(--text) !important;
    border-color: var(--primary-dark) !important;
  }
  .table-hover tbody tr:hover {
    background: var(--primary) !important;
    color: #fff !important;
  }
  .btn-primary, .btn-primary:focus, .btn-primary:active {
    background: var(--primary) !important;
    border-color: var(--primary-dark) !important;
    color: #fff !important;
  }
  .btn-warning, .btn-warning:focus, .btn-warning:active {
    background: var(--warning) !important;
    border-color: var(--warning) !important;
    color: #222 !important;
  }
  .btn-danger, .btn-danger:focus, .btn-danger:active {
    background: var(--danger) !important;
    border-color: var(--danger) !important;
    color: #fff !important;
  }
  .btn-success, .btn-success:focus, .btn-success:active {
    background: var(--success) !important;
    border-color: var(--success) !important;
    color: #fff !important;
  }
  .modal-content {
    background: var(--bg) !important;
    color: var(--text) !important;
  }
  .form-control, .form-select {
    background: var(--bg) !important;
    color: var(--text) !important;
    border-color: var(--primary-dark) !important;
  }
  .form-control:focus, .form-select:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25) !important;
  }
  /* ...existing code... */
</style>
</head>
<body>
<style>
  .logo-container { margin: 20px; padding: 10px; }
  .logo-container .logo { width: 100%; height: 100%; border-radius: 5%; object-fit: cover; }
  .admin-panel { font-size: 30px; color: #ccc; margin: 0; }
</style>
<nav class="sidebar" role="navigation" aria-label="Menu principal">
  <div class="logo-container">
    <center>
      <img src="../../../img/logo.jpg" alt="Logo Betuole" class="logo">
      <p class="admin-panel">Admin Panel</p>
    </center>
  </div>
  <a href="../index.php"><i class="fa-solid fa-chart-bar"></i><span> Tableau de bord</span></a>
  <a href="../ges_utilisateurs/admins.php"><i class="fa-solid fa-user-shield"></i><span> Gérer Admins</span></a>
  <a href="../ges_utilisateurs/apprenants.php"><i class="fa-solid fa-user-graduate"></i><span> Gérer Apprenants</span></a>
  <a href="../ges_formations/index.php"><i class="fa-solid fa-book-open"></i><span> Formations</span></a>
  <a href="../ges_paiements/index.php"><i class="fa-solid fa-money-bill-wave"></i><span> Paiements</span></a>
  <a href="#" class="active" aria-current="page"><i class="fa-solid fa-newspaper"></i><span> Actualités</span></a>
  <a href="../ges_annees/index.php"><i class="fa-solid fa-calendar"></i><span> Années d'activité</span></a>
  <a href="../ges_archives/index.php"><i class="fa-solid fa-archive"></i><span> Archives</span></a>
  <a href="../../auth/logout.php" class="btn btn-danger logout-btn" role="button" aria-label="Se déconnecter">
    <i class="fa-solid fa-right-from-bracket"></i><span> Déconnexion</span>
  </a>
</nav>
<header class="topbar" role="banner">
  <button id="themeToggleBtn" aria-label="Changer thème clair/sombre" title="Changer le thème clair/sombre">
    <i class="fa-solid fa-moon"></i>
  </button>
</header>
<main class="content" role="main" tabindex="-1" aria-live="polite">
  <?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-info text-center">
    <?= $_SESSION['message'] ?>
    <?php unset($_SESSION['message']); ?>
  </div>
<?php endif; ?>
<!-- contenue de la page soté droite -->
 <?php
require_once '../../../config.php';

// Connexion DB
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $actualites = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<?php if (isset($_GET['update']) && $_GET['update'] == 1): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Modification réussie',
      text: 'L’actualité a été mise à jour.',
    });
  </script>
<?php elseif (isset($_GET['update']) && $_GET['update'] == 0): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Erreur',
      text: 'Impossible de modifier l’actualité.',
    });
  </script>
<?php endif; ?>


<div class="container my-5">
  <div class="container-box">
    <div class="header-bar d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Liste des Actualités</h4>
      <!-- Bouton déclencheur du modal -->
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addActuModal">
        <i class="bi bi-plus-circle me-1"></i> Ajouter
        </button>
    </div>
    
    <div class="p-3">
    <table id="actualitesTable" class="table table-bordered table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>Image</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($actualites as $actu): ?>
          <tr>
            <td>
              <?php if (!empty($actu['image'])): ?>
                <img src="<?= htmlspecialchars($actu['image']) ?>" alt="Actu">
              <?php else: ?>
                <span class="text-muted">Aucune</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($actu['titre']) ?></td>
            <td><?= htmlspecialchars(substr($actu['description'], 0, 80)) ?>...</td>
            <td><?= date("d/m/Y", strtotime($actu['date_publication'])) ?></td>
            <td class="action-btns">
                <button 
                    class="btn btn-sm btn-warning edit-btn"
                    data-id="<?= $actu['id'] ?>"
                    data-titre="<?= htmlspecialchars($actu['titre'], ENT_QUOTES) ?>"
                    data-description="<?= htmlspecialchars($actu['description'], ENT_QUOTES) ?>"
                    data-date="<?= date('Y-m-d\TH:i', strtotime($actu['date_publication'])) ?>"
                    data-statut="<?= $a['statut'] ?>"
                    data-image="<?= htmlspecialchars($actu['image'], ENT_QUOTES) ?>"
                    >
                    <i class="fas fa-edit"></i>
                </button>
                <a href="#" class="btn btn-sm btn-danger" onclick="confirmerSuppression(<?= $actu['id'] ?>)">
                  <i class="fa-solid fa-trash"></i>
                </a>


            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<!-- modal de l'ajout d'une actualité -->

<!-- Modal d'ajout d'une actualité -->
<div class="modal fade" id="addActuModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="ajouter.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Ajouter une Actualité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="titre" class="form-label">Titre</label>
          <input type="text" name="titre" id="titre" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Image (facultative)</label>
          <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>


<!-- end ajout -->

<!-- modal de la mise ajout ou modification -->

<!-- Modal de modification d’actualité -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="modifier.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">Modifier l’actualité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <input type="hidden" name="old_image" id="edit-old-image">

        <div class="mb-3">
          <label for="edit-titre">Titre</label>
          <input type="text" name="titre" id="edit-titre" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="edit-description">Description</label>
          <textarea name="description" id="edit-description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label for="edit-image">Image</label>
          <input type="file" name="image" id="edit-image" class="form-control" accept="image/*">
          <small class="text-muted">Laisser vide pour conserver l'image actuelle.</small>
        </div>

        <div class="mb-3">
          <label for="edit-date">Date de publication</label>
          <input type="datetime-local" name="date_publication" id="edit-date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="edit-statut">Statut</label>
          <select name="statut" id="edit-statut" class="form-select" required>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>



<!-- end modification -->

</main>
<!-- end main -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
  // Script thème clair/sombre identique à admins.php
  const themeToggleBtn = document.getElementById('themeToggleBtn');
  const htmlEl = document.documentElement;
  function setTheme(theme) {
    htmlEl.setAttribute('data-theme', theme);
    if(theme === 'dark') {
      themeToggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
    } else {
      themeToggleBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
    }
    localStorage.setItem('theme', theme);
  }
  themeToggleBtn.addEventListener('click', () => {
    const current = htmlEl.getAttribute('data-theme') || 'light';
    setTheme(current === 'light' ? 'dark' : 'light');
  });
  const savedTheme = localStorage.getItem('theme') || 'light';
  setTheme(savedTheme);
  $(document).ready(function() {
    $('#actualitesTable').DataTable({
      scrollY: '400px',
      scrollX: true,
      scrollCollapse: true,
      paging: true,
      searching: true,
      responsive: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
      },
      dom: '<"d-flex justify-content-between mb-3"lfB>rtip',
      buttons: [
        {
          extend: 'copy',
          className: 'btn btn-sm btn-danger'
        },
        {
          extend: 'csv',
          className: 'btn btn-sm btn-danger'
        },
        {
          extend: 'excel',
          className: 'btn btn-sm btn-danger'
        },
        {
          extend: 'pdf',
          className: 'btn btn-sm btn-danger'
        },
        {
          extend: 'print',
          className: 'btn btn-sm btn-danger'
        }
      ]
    });
  });
  // ...script DataTables, modals, etc. comme admins.php...
</script>
</body>
</html>
<?php $pdo = null; ?>


