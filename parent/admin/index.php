<?php
require_once '../../config.php';

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
<title>Betuole Académie - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<!-- alerte de bienvenue sur le dashboard -->

<?php if (!isset($_GET['success'])): ?>
  <script>
    Swal.fire({
      icon: 'info',
      title: 'Bienvenue',
      text: 'Bienvenue sur votre tableau de Bord Admin.',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>

<!-- end alert -->
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
        <img src="../../img/logo.jpg" alt="Logo Betuole" class="logo">
        <p class="admin-panel">Admin Panel</p>
      </center>
    </div>
  <a href="#" class="active" aria-current="page"><i class="fa-solid fa-chart-bar"></i><span> Tableau de bord</span></a>
  <a href="ges_utilisateurs/admins.php"><i class="fa-solid fa-user-shield"></i><span> Gérer Admins</span></a>
  <a href="ges_utilisateurs/apprenants.php"><i class="fa-solid fa-user-graduate"></i><span> Gérer Apprenants</span></a>
  <a href="ges_formations/index.php"><i class="fa-solid fa-book-open"></i><span> Formations</span></a>
  <a href="ges_paiements/index.php"><i class="fa-solid fa-money-bill-wave"></i><span> Paiements</span></a>
  <a href="ges_actualites/index.php"><i class="fa-solid fa-newspaper"></i><span> Actualités</span></a>
  <a href="ges_annees/index.php"><i class="fa-solid fa-calendar"></i><span> Années d'activité</span></a>
  <a href="ges_archives/index.php"><i class="fa-solid fa-archive"></i><span> Archives</span></a>
  <a href="../auth/logout.php" class="btn btn-danger logout-btn" role="button" aria-label="Se déconnecter">
    <i class="fa-solid fa-right-from-bracket"></i><span> Déconnexion</span>
  </a>
</nav>

<header class="topbar" role="banner">
  <button id="themeToggleBtn" aria-label="Changer thème clair/sombre" title="Changer le thème clair/sombre">
    <i class="fa-solid fa-moon"></i>
  </button>
</header>

<main class="content" role="main" tabindex="-1" aria-live="polite">
  <h1>Bienvenue sur le Tableau de bord</h1>
  <p class="lead">Aperçu global des activités de Betuole Académie</p>

  <section class="stats-grid" aria-label="Statistiques principales">
    <article class="stat-card stat-admin" tabindex="0" aria-label="Nombre d'administrateurs">
      <h4><i class="fa-solid fa-user-shield"></i> Administrateurs</h4>
      <p class="value"><?= $stats['admins'] ?></p>
    </article>
    <article class="stat-card stat-apprenant" tabindex="0" aria-label="Nombre d'apprenants">
      <h4><i class="fa-solid fa-user-graduate"></i> Apprenants</h4>
      <p class="value"><?= $stats['apprenants'] ?></p>
    </article>
    <article class="stat-card stat-formation" tabindex="0" aria-label="Nombre de formations">
      <h4><i class="fa-solid fa-book-open"></i> Formations</h4>
      <p class="value"><?= $stats['formations'] ?></p>
    </article>
    <article class="stat-card stat-paiement" tabindex="0" aria-label="Nombre de paiements">
      <h4><i class="fa-solid fa-money-bill-wave"></i> Paiements</h4>
      <p class="value"><?= $stats['paiements'] ?></p>
    </article>
    <article class="stat-card stat-actualite" tabindex="0" aria-label="Nombre d'actualités">
      <h4><i class="fa-solid fa-newspaper"></i> Actualités</h4>
      <p class="value"><?= $stats['actualites'] ?></p>
    </article>
    <article class="stat-card stat-annee-active" tabindex="0" aria-label="Année active">
      <h4><i class="fa-solid fa-calendar"></i> Année active</h4>
      <p class="value"><?= htmlspecialchars($stats['annee_active']) ?></p>
    </article>
    <article class="stat-card stat-archive" tabindex="0" aria-label="Nombre d'archives">
      <h4><i class="fa-solid fa-archive"></i> Archives</h4>
      <p class="value"><?= $stats['archives'] ?></p>
    </article>
    <article class="stat-card stat-annees-archivees" tabindex="0" aria-label="Nombre d'années archivées">
      <h4><i class="fa-solid fa-box-archive"></i> Années archivées</h4>
      <p class="value"><?= $stats['annees_archivees'] ?></p>
    </article>
  </section>

  <section id="chartsTabs" class="mt-5" aria-label="Graphiques d'évolution">
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="global-tab" data-bs-toggle="tab" data-bs-target="#global" type="button" role="tab" aria-controls="global" aria-selected="true">Global</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">Utilisateurs</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="formations-tab" data-bs-toggle="tab" data-bs-target="#formations" type="button" role="tab" aria-controls="formations" aria-selected="false">Formations</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="paiements-tab" data-bs-toggle="tab" data-bs-target="#paiements" type="button" role="tab" aria-controls="paiements" aria-selected="false">Paiements</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="actualites-tab" data-bs-toggle="tab" data-bs-target="#actualites" type="button" role="tab" aria-controls="actualites" aria-selected="false">Actualités</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="annees-tab" data-bs-toggle="tab" data-bs-target="#annees" type="button" role="tab" aria-controls="annees" aria-selected="false">Années d'activité</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="archives-tab" data-bs-toggle="tab" data-bs-target="#archives" type="button" role="tab" aria-controls="archives" aria-selected="false">Archives</button>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="global" role="tabpanel" aria-labelledby="global-tab">
        <canvas id="globalChart" class="mt-4" role="img" aria-label="Graphique en barres des statistiques globales"></canvas>
      </div>
      <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
        <canvas id="usersChart" class="mt-4" role="img" aria-label="Graphique en ligne des utilisateurs"></canvas>
      </div>
      <div class="tab-pane fade" id="formations" role="tabpanel" aria-labelledby="formations-tab">
        <canvas id="formationsChart" class="mt-4" role="img" aria-label="Graphique en ligne des formations"></canvas>
      </div>
      <div class="tab-pane fade" id="paiements" role="tabpanel" aria-labelledby="paiements-tab">
        <canvas id="paiementsChart" class="mt-4" role="img" aria-label="Graphique en ligne des paiements"></canvas>
      </div>
      <div class="tab-pane fade" id="actualites" role="tabpanel" aria-labelledby="actualites-tab">
        <canvas id="actualitesChart" class="mt-4" role="img" aria-label="Graphique en ligne des actualités"></canvas>
      </div>
      <div class="tab-pane fade" id="annees" role="tabpanel" aria-labelledby="annees-tab">
        <canvas id="anneesChart" class="mt-4" role="img" aria-label="Graphique en ligne des années d'activité"></canvas>
      </div>
      <div class="tab-pane fade" id="archives" role="tabpanel" aria-labelledby="archives-tab">
        <canvas id="archivesChart" class="mt-4" role="img" aria-label="Graphique en ligne des archives"></canvas>
      </div>
    </div>
  </section>
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

  // Graphiques lignes par module
  const usersChart = createLineChart(usersCtx, 'Utilisateurs', usersData, '#198754');
  const formationsChart = createLineChart(formationsCtx, 'Formations', formationsData, '#ffc107');
  const paiementsChart = createLineChart(paiementsCtx, 'Paiements', paiementsData, '#6f42c1');
  const actualitesChart = createLineChart(actualitesCtx, 'Actualités', actualitesData, '#20c997');
  const anneesChart = createLineChart(anneesCtx, 'Années d\'activité', anneesData, '#fd7e14');
  const archivesChart = createLineChart(archivesCtx, 'Archives', archivesData, '#e0a800');

 
</script>
</body>
</html>
<?php $pdo = null; ?>
