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
<title>Gestion des Paiements</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
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
</style>
</head>
<body>

<style>
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
</style>
<nav class="sidebar" role="navigation" aria-label="Menu principal">
  <div class="logo-container">
      <center>
        <img src="../../../img/logo.jpg" alt="Logo Betuole" class="logo">
        <p class="admin-panel">Admin Panel</p>
      </center>
    </div>
  <a href="../index.php" class="active" aria-current="page"><i class="fa-solid fa-chart-bar"></i><span> Tableau de bord</span></a>
  <a href="../ges_utilisateurs/admins.php"><i class="fa-solid fa-user-shield"></i><span> Gérer Admins</span></a>
  <a href="../ges_utilisateurs/apprenants.php"><i class="fa-solid fa-user-graduate"></i><span> Gérer Apprenants</span></a>
  <a href="../ges_formations/index.php"><i class="fa-solid fa-book-open"></i><span> Formations</span></a>
  <a href="#"><i class="fa-solid fa-money-bill-wave"></i><span> Paiements</span></a>
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

<!-- contenue de la page -->
 

<main class="content" role="main" tabindex="-1" aria-live="polite">
  <h2>Archives automatiques téléchargeables</h2>
  <div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fa-solid fa-book-open fa-2x mb-2 text-primary"></i>
          <h5 class="card-title">Formations</h5>
          <a href="export_archives.php?module=ges_formations" class="btn btn-success mt-2" target="_blank">ZIP/CSV</a>
          <a href="export_archives.php?module=ges_formations&format=pdf" class="btn btn-danger mt-2" target="_blank">PDF</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fa-solid fa-users fa-2x mb-2 text-success"></i>
          <h5 class="card-title">Utilisateurs</h5>
          <a href="export_archives.php?module=ges_utilisateurs" class="btn btn-success mt-2" target="_blank">ZIP/CSV</a>
          <a href="export_archives.php?module=ges_utilisateurs&format=pdf" class="btn btn-danger mt-2" target="_blank">PDF</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fa-solid fa-envelope fa-2x mb-2 text-warning"></i>
          <h5 class="card-title">Messages visiteurs</h5>
          <a href="export_archives.php?module=messages_visiteurs" class="btn btn-success mt-2" target="_blank">ZIP/CSV</a>
          <a href="export_archives.php?module=messages_visiteurs&format=pdf" class="btn btn-danger mt-2" target="_blank">PDF</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fa-solid fa-money-bill-wave fa-2x mb-2 text-purple"></i>
          <h5 class="card-title">Paiements</h5>
          <a href="export_archives.php?module=ges_paiements" class="btn btn-success mt-2" target="_blank">ZIP/CSV</a>
          <a href="export_archives.php?module=ges_paiements&format=pdf" class="btn btn-danger mt-2" target="_blank">PDF</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fa-solid fa-newspaper fa-2x mb-2 text-info"></i>
          <h5 class="card-title">Actualités</h5>
          <a href="export_archives.php?module=ges_actualites" class="btn btn-success mt-2" target="_blank">ZIP/CSV</a>
          <a href="export_archives.php?module=ges_actualites&format=pdf" class="btn btn-danger mt-2" target="_blank">PDF</a>
        </div>
      </div>
    </div>
  </div>

  <h2 class="mt-5">Archives des années antérieures déjà archivées</h2>
  <table class="table table-bordered table-striped mt-3">
    <thead>
      <tr>
        <th>Année</th>
        <th>Date d'archivage</th>
        <th>Téléchargement</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $archives = $pdo->query("SELECT id, annee, date_archive, donnees FROM archives ORDER BY date_archive DESC")->fetchAll(PDO::FETCH_ASSOC);
      foreach ($archives as $arch) :
        $id = $arch['id'];
        $annee = htmlspecialchars($arch['annee']);
        $date = htmlspecialchars($arch['date_archive']);
      ?>
        <tr>
          <td><?= $annee ?></td>
          <td><?= $date ?></td>
          <td>
            <a href="export_archives.php?archive_id=<?= $id ?>&format=zip" class="btn btn-success btn-sm">ZIP/CSV</a>
            <a href="export_archives.php?archive_id=<?= $id ?>&format=pdf" class="btn btn-danger btn-sm">PDF</a>
            <a href="export_archives.php?archive_id=<?= $id ?>&format=sql" class="btn btn-secondary btn-sm">SQL</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <!-- contenu de la page côté droite -->
  
</main>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const themeToggleBtn = document.getElementById('themeToggleBtn');
  const htmlEl = document.documentElement;

  // Gérer thème clair/sombre
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

  // Graphiques Chart.js
  const globalCtx = document.getElementById('globalChart').getContext('2d');
  const usersCtx = document.getElementById('usersChart').getContext('2d');
  const formationsCtx = document.getElementById('formationsChart').getContext('2d');
  const paiementsCtx = document.getElementById('paiementsChart').getContext('2d');
  const actualitesCtx = document.getElementById('actualitesChart').getContext('2d');
  const anneesCtx = document.getElementById('anneesChart').getContext('2d');
  const archivesCtx = document.getElementById('archivesChart').getContext('2d');

  // Données fictives à remplacer par requêtes dynamiques
  const moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
  const usersData = [10, 25, 30, 40, 50, 65];
  const formationsData = [5, 15, 20, 25, 35, 40];
  const paiementsData = [7, 20, 22, 30, 35, 45];
  const actualitesData = [2, 5, 8, 12, 14, 20];
  const anneesData = [1, 1, 1, 1, 1, 1];
  const archivesData = [1, 0, 0, 2, 1, 3];

  // Fonction de création graphique ligne simple
  function createLineChart(ctx, label, data, color) {
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: moisLabels,
        datasets: [{
          label,
          data,
          fill: false,
          borderColor: color,
          backgroundColor: color,
          tension: 0.3,
          pointRadius: 5,
          pointHoverRadius: 7,
          borderWidth: 3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.1)' }
          },
          x: {
            grid: { color: 'rgba(0,0,0,0.05)' }
          }
        },
        plugins: {
          legend: { display: true, labels: { font: { size: 14 } } },
          tooltip: { enabled: true, mode: 'nearest', intersect: false }
        }
      }
    });
  }

  // Graphique global (barres)
  const globalChart = new Chart(globalCtx, {
    type: 'bar',
    data: {
      labels: ['Admins', 'Apprenants', 'Formations', 'Paiements', 'Actualités', 'Année active', 'Archives', 'Années archivées'],
      datasets: [{
        label: 'Statistiques générales',
        data: [
          <?= $stats['admins'] ?>,
          <?= $stats['apprenants'] ?>,
          <?= $stats['formations'] ?>,
          <?= $stats['paiements'] ?>,
          <?= $stats['actualites'] ?>,
          1,
          <?= $stats['archives'] ?>,
          <?= $stats['annees_archivees'] ?>
        ],
        backgroundColor: [
          '#0d6efd','#198754','#ffc107','#6f42c1','#20c997','#fd7e14','#e0a800','#5a3298'
        ],
        borderColor: [
          '#0b5ed7','#157347','#d39e00','#5a2eae','#148f77','#b35900','#a07c00','#3e2369'
        ],
        borderWidth: 1,
        borderRadius: 8,
        barPercentage: 0.7,
        categoryPercentage: 0.6,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      },
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: 'Vue d’ensemble de Betuole Académie',
          font: { size: 20, weight: '700' }
        },
        tooltip: { enabled: true }
      }
    }
  });

  //alerte paiement ok


  // Graphiques lignes par module
  const usersChart = createLineChart(usersCtx, 'Utilisateurs', usersData, '#198754');
  const formationsChart = createLineChart(formationsCtx, 'Formations', formationsData, '#ffc107');
  const paiementsChart = createLineChart(paiementsCtx, 'Paiements', paiementsData, '#6f42c1');
  const actualitesChart = createLineChart(actualitesCtx, 'Actualités', actualitesData, '#20c997');
  const anneesChart = createLineChart(anneesCtx, 'Années actives', anneesData, '#fd7e14');
  const archivesChart = createLineChart(archivesCtx, 'Archives', archivesData, '#5a3298');

  // Exemple d'alerte succès après téléchargement
  document.querySelectorAll('a.btn-success').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const module = btn.closest('.card-body').querySelector('.card-title').innerText;
      Swal.fire({
        icon: 'success',
        title: 'Téléchargement lancé',
        text: `L'archive de ${module} est en cours de téléchargement.`,
        confirmButtonText: 'OK',
        timer: 3000,
        timerProgressBar: true,
        willClose: () => {
          clearTimeout(timer);
        }
      });
    });
  });
</script>
</body>
</html>