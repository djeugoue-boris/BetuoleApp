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

// Récupération des formations
$formations = []; // Toujours initialiser comme tableau
try {
    $stmt = $pdo->query("SELECT * FROM formations ORDER BY id DESC");
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Optionnel : afficher ou log l'erreur
}

?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gestion des Formations</title>
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
  .stat-paiement { background: linear-gradient(135deg,#6f42c1,#4e3283); }
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

  /* Style violent DataTables */
  table.dataTable {
    background: #181818;
    color: #fff;
    border-radius: 8px;
    overflow: hidden;
  }
  table.dataTable thead {
    background: #111;
    color: #fff;
  }
  table.dataTable tbody tr:hover {
    background: #b30000 !important;
    color: #fff;
  }
  .dt-button, .buttons-html5, .buttons-print {
    background: #b30000 !important;
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
    color: #fff !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    background: #222 !important;
    color: #fff !important;
    border-radius: 3px;
    margin: 0 2px;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #b30000 !important;
    color: #fff !important;
  }
</style>
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] === 'suppression'): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Supprimée !',
    text: 'La formation a bien été supprimée.',
    confirmButtonColor: '#198754',
    timer: 2000
  });
</script>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Erreur',
    text: '<?= htmlspecialchars($_GET['error']) ?>',
    confirmButtonColor: '#dc3545'
  });
</script>
<?php endif; ?>


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
  <a href="#" class="active" aria-current="page"><i class="fa-solid fa-book-open"></i><span> Formations</span></a>
  <a href="../ges_paiements/index.php"><i class="fa-solid fa-money-bill-wave"></i><span> Paiements</span></a>
  <a href="../ges_actualites/index.php"><i class="fa-solid fa-newspaper"></i><span> Actualités</span></a>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-4">📚 Liste des Formations</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFormationModal">
          <i class="fa fa-plus-circle me-1"></i> Ajouter une Formation
        </button>
    </div>

  <div style="width:100%; max-width:100vw; overflow-x:auto; margin-bottom:2rem;">
    <table id="formationsTable" class="table table-bordered table-hover align-middle" style="min-width:1100px; width:100%;">
      <thead class="table-dark">
        <tr>
          <th>Image</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Prix</th>
          <th>Quantité</th>
          <th>Date d'ajout</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($formations as $formation): ?>
          <tr>
            <td>
              <?php if (!empty($formation['image'])): ?>
                <img src="<?= htmlspecialchars($formation['image']) ?>" alt="Image" width="60" class="zoomable-img" style="cursor: zoom-in;">
              <?php else: ?>
                <span class="text-muted">Aucune</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($formation['nom']) ?></td>
            <td><?= htmlspecialchars($formation['description']) ?></td>
            <td><?= number_format($formation['prix'], 2, ',', ' ') ?> FCFA</td>
            <td><?= $formation['quantite'] ?></td>
            <td><?= $formation['date_ajout'] ?></td>
            <td>
              <button 
                class="btn btn-sm btn-warning edit-btn"
                data-id="<?= $formation['id'] ?>"
                data-nom="<?= htmlspecialchars($formation['nom'], ENT_QUOTES) ?>"
                data-description="<?= htmlspecialchars($formation['description'], ENT_QUOTES) ?>"
                data-prix="<?= $formation['prix'] ?>"
                data-quantite="<?= $formation['quantite'] ?>"
                data-image="<?= htmlspecialchars($formation['image'], ENT_QUOTES) ?>"
              >
                <i class="fas fa-edit"></i> 
              </button>
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                      data-id="<?= $formation['id'] ?>">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<!-- Modal de zoom image -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Aperçu de l'image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" alt="Image" class="img-fluid" id="zoomedImage">
      </div>
    </div>
  </div>
</div>
<!-- modal d'ajout d'une formation -->
 <!-- Modal ajout formation -->
<div class="modal fade text-dark" id="addFormationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="add_formation.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Ajouter une Formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Nom</label>
          <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-2">
          <label>Prix</label>
          <input type="number" name="prix" step="0.01" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Quantité</label>
          <input type="number" name="quantite" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal de modification formation -->
<div class="modal fade text-dark" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="update_formation.php" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Modifier une Formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <input type="hidden" name="old_image" id="edit-old-image">

        <div class="mb-2">
          <label>Nom</label>
          <input type="text" name="nom" id="edit-nom" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Description</label>
          <textarea name="description" id="edit-description" class="form-control"></textarea>
        </div>
        <div class="mb-2">
          <label>Prix</label>
          <input type="number" name="prix" id="edit-prix" step="0.01" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Quantité</label>
          <input type="number" name="quantite" id="edit-quantite" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Nouvelle Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Laisser vide pour conserver l’image actuelle.</small>
        </div>
      </div>


      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>




<?php $pdo = null; ?>

</main>
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
    $('#formationsTable').DataTable({
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
  // ...modals, etc. comme admins.php...
</script>
</body>
</html>
<?php $pdo = null; ?>


