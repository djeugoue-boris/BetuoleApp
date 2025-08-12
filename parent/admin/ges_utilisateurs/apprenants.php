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
<title>Betuole Académie - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<!-- CSS DataTables Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- DataTables Buttons pour export -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />

<!-- JS jQuery et DataTables -->
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

    /* Style violent pour la table apprenants */
    .dataTable {
    background: #0d1117 !important;
    color: #fff !important;
    border-radius: 18px !important;
    box-shadow: 0 0 30px #0d6efd99, 0 0 10px #dc3545cc;
    font-size: 1.1rem;
    overflow: hidden;
  }
  .dataTable thead {
    background: linear-gradient(90deg, #0d6efd 60%, #dc3545 100%) !important;
    color: #fff !important;
    font-size: 1.15rem;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
  .dataTable tbody tr {
    background: #161b22 !important;
    transition: background 0.2s;
  }
  .dataTable tbody tr:hover {
    background: #dc3545 !important;
    color: #fff !important;
    font-weight: bold;
    box-shadow: 0 0 10px #dc3545cc;
  }
  .dataTable td, .dataTable th {
    padding: 16px 12px !important;
    border: none !important;
  }
  .dataTable .btn-warning {
    background: #ffc107;
    color: #222;
    border: none;
    font-weight: bold;
    box-shadow: 0 0 8px #ffc10799;
  }
  .dataTable .btn-danger {
    background: #dc3545;
    color: #fff;
    border: none;
    font-weight: bold;
    box-shadow: 0 0 8px #dc354599;
  }
  .dataTable .btn-warning:hover, .dataTable .btn-danger:hover {
    filter: brightness(1.2);
    transform: scale(1.08);
  }
  .dataTables_filter input {
    background: #222 !important;
    color: #fff !important;
    border: 2px solid #0d6efd !important;
    border-radius: 8px;
    font-size: 1.1rem;
    margin-left: 0.5em;
  }
  .dataTables_length select {
    background: #222 !important;
    color: #fff !important;
    border: 2px solid #0d6efd !important;
    border-radius: 8px;
    font-size: 1.1rem;
    margin-left: 0.5em;
  }
  .dataTables_paginate .paginate_button {
    background: #0d6efd !important;
    color: #fff !important;
    border-radius: 6px !important;
    margin: 0 2px;
    font-weight: bold;
    border: none !important;
    transition: background 0.2s;
  }
  .dataTables_paginate .paginate_button.current, .dataTables_paginate .paginate_button:hover {
    background: #dc3545 !important;
    color: #fff !important;
    box-shadow: 0 0 8px #dc3545cc;
  }
  .dt-buttons .btn {
    background: #0d6efd !important;
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: bold;
    margin-right: 8px;
    border: none !important;
    box-shadow: 0 0 8px #0d6efd99;
  }
  .dt-buttons .btn:hover {
    background: #dc3545 !important;
    color: #fff !important;
    box-shadow: 0 0 12px #dc3545cc;
  }
</style>
</head>
<body>
<style>

</style>
<nav class="sidebar" role="navigation" aria-label="Menu principal">
  <div class="logo-container">
      <center>
        <img src="../../../img/logo.jpg" alt="Logo Betuole" class="logo">
        <p class="admin-panel">Admin Panel</p>
      </center>
    </div>
  <a href="../index.php" class="active" aria-current="page"><i class="fa-solid fa-chart-bar"></i><span> Tableau de bord</span></a>
  <a href="admins.php"><i class="fa-solid fa-user-shield"></i><span> Gérer Admins</span></a>
  <a href="#"><i class="fa-solid fa-user-graduate"></i><span> Gérer Apprenants</span></a>
  <a href="../ges_formations/index.php"><i class="fa-solid fa-book-open"></i><span> Formations</span></a>
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

<!-- contenue de la page -->

<?php
require_once '../../../config.php'; // connexion PDO

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Suppression d'un admin (via ?delete_id=)
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    // Sécurité: on ne supprime que si c'est un admin
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id = ? AND role = 'admin'");
    $stmtCheck->execute([$delete_id]);
    if ($stmtCheck->fetchColumn() > 0) {
        $stmtDelete = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmtDelete->execute([$delete_id]);
        header("Location: admins.php?msg=Admin supprimé avec succès");
        exit;
    } else {
        $msg = "Admin introuvable ou suppression impossible.";
    }
}

// Récupération des admins
$stmt = $pdo->query("SELECT id, matricule, nom, prenom, email, telephone, date_inscription FROM utilisateurs WHERE role = 'admin' ORDER BY date_inscription DESC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
    <main class="content" role="main" tabindex="-1" aria-live="polite">
    <?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Récupération
$admins = $pdo->query("SELECT * FROM utilisateurs WHERE role = 'admin' ORDER BY date_inscription DESC")->fetchAll();
?>

<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Suppression d'un apprenant
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id = ? AND role = 'apprenant'");
    $stmtCheck->execute([$delete_id]);
    if ($stmtCheck->fetchColumn() > 0) {
        $stmtDelete = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmtDelete->execute([$delete_id]);
        echo("Bravo, suppression reussie! <a href='apprenants.php' style='color:green; font-weight: bold;'>Continuer maintenant</a>");
        exit;
    } else {
        $msg = "Apprenant introuvable ou suppression impossible.";
    }
}

$apprenants = $pdo->query("SELECT * FROM utilisateurs WHERE role = 'apprenant' ORDER BY date_inscription DESC")->fetchAll();
?>

<div class="container-fluid">
  <h4 class="mb-4">🎓 Liste des apprenants</h4>
  <div class="d-flex justify-content-between mb-3">
    <h4><i class="fa fa-user-plus me-2"></i> </h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class=" fa fa-plus mx-2"></i>Ajouter un Apprenant 
    </button>
  </div>
  <table id="apprenantsTable" class="table table-bordered table-hover table-striped table-responsive nowrap align-middle" style="width:100%">
  <thead class="table-dark">
    <tr>
      <th>Matricule</th>
      <th>Nom & Prénom</th>
      <th>Email</th>
      <th>Téléphone</th>
      <th>Inscrit le</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($apprenants as $apprenant): ?>
      <tr>
        <td><?= htmlspecialchars($apprenant['matricule']) ?></td>
        <td><?= htmlspecialchars($apprenant['nom'] . ' ' . $apprenant['prenom']) ?></td>
        <td><?= htmlspecialchars($apprenant['email']) ?></td>
        <td><?= htmlspecialchars($apprenant['telephone']) ?></td>
        <td><?= htmlspecialchars($apprenant['date_inscription']) ?></td>
        <td>
          <button class="btn btn-sm btn-warning edit-btn"
            data-id="<?= $apprenant['id'] ?>"
            data-nom="<?= $apprenant['nom'] ?>"
            data-prenom="<?= $apprenant['prenom'] ?>"
            data-email="<?= $apprenant['email'] ?>"
            data-telephone="<?= $apprenant['telephone'] ?>"
            data-matricule="<?= $apprenant['matricule'] ?>">
            <i class="fas fa-edit"></i>
          </button>
          <a href="apprenants.php?delete_id=<?= $apprenant['id'] ?>" onclick="return confirm('Supprimer cet apprenant ?')" class="btn btn-sm btn-danger">
            <i class="fas fa-trash-alt"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</div>
<!-- Modal : Ajouter un Utilisateur -->
<div class="modal fade text-dark" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="add_apprenant.php" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Ajouter un Apprenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Matricule</label>
          <input type="text" name="matricule" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Nom</label>
          <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Prénom</label>
          <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Sexe</label>
          <select name="sexe" class="form-select" required>
            <option value="">-- Choisir le sexe --</option>
            <option value="M">Masculin</option>
            <option value="F">Féminin</option>
          </select>
        </div>
        <div class="mb-2">
          <label>Date de naissance</label>
          <input type="date" name="date_naissance" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>CNI</label>
          <input type="text" name="cni" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Téléphone</label>
          <input type="text" name="telephone" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Rôle</label>
          <select name="role" class="form-select" required>
            <option value="apprenant">Apprenant</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de modification apprenant -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="edit_apprenant.php" class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">Modifier un Utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        
        <div class="mb-2">
          <label>Matricule</label>
          <input type="text" name="matricule" id="edit-matricule" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Nom</label>
          <input type="text" name="nom" id="edit-nom" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Prénom</label>
          <input type="text" name="prenom" id="edit-prenom" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Sexe</label>
          <select name="sexe" id="edit-sexe" class="form-select" required>
            <option value="M">Masculin</option>
            <option value="F">Féminin</option>
          </select>
        </div>

        <div class="mb-2">
          <label>Date de naissance</label>
          <input type="date" name="date_naissance" id="edit-date-naissance" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>CNI</label>
          <input type="text" name="cni" id="edit-cni" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Email</label>
          <input type="email" name="email" id="edit-email" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Téléphone</label>
          <input type="text" name="telephone" id="edit-telephone" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Rôle</label>
          <select name="role" id="edit-role" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="apprenant">Apprenant</option>
            <option value="assistant">Assistant</option>
            <option value="superadmin">Super Admin</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const editModal = new bootstrap.Modal(document.getElementById('editModal'));
  const editButtons = document.querySelectorAll('.edit-btn');

  editButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit-id').value = btn.dataset.id;
      document.getElementById('edit-matricule').value = btn.dataset.matricule;
      document.getElementById('edit-nom').value = btn.dataset.nom;
      document.getElementById('edit-prenom').value = btn.dataset.prenom;
      document.getElementById('edit-email').value = btn.dataset.email;
      document.getElementById('edit-telephone').value = btn.dataset.telephone;
      editModal.show();
    });
  });
</script>


</main>
 <!-- fin contenue de la page -->

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

  // edit apprenant ok 
  <?php if (isset($_GET['success']) && $_GET['success'] == 'modif-apprenant'): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Modification réussie',
    text: 'Les informations de l’apprenant ont été mises à jour avec succès.',
    confirmButtonColor: '#28a745',
    timer: 3000
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Erreur',
    text: '<?= ($_GET['error'] === "modif-echouee") ? "Échec de la modification." : "Veuillez remplir tous les champs correctement." ?>',
    confirmButtonColor: '#dc3545'
  });
</script>
<?php endif; ?>


  

  // Graphiques lignes par module
  const usersChart = createLineChart(usersCtx, 'Utilisateurs', usersData, '#198754');
  const formationsChart = createLineChart(formationsCtx, 'Formations', formationsData, '#ffc107');
  const paiementsChart = createLineChart(paiementsCtx, 'Paiements', paiementsData, '#6f42c1');
  const actualitesChart = createLineChart(actualitesCtx, 'Actualités', actualitesData, '#20c997');
  const anneesChart = createLineChart(anneesCtx, 'Années d\'activité', anneesData, '#fd7e14');
  const archivesChart = createLineChart(archivesCtx, 'Archives', archivesData, '#e0a800');

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function() {
    $('#apprenantsTable').DataTable({
      scrollY: '400px',
      scrollX: true,
      scrollCollapse: true,
      paging: true,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
      },
      dom: 'Bfrtip',
      buttons: [
        { extend: 'copy', text: 'Copier' },
        { extend: 'csv', text: 'CSV' },
        { extend: 'excel', text: 'Excel' },
        { extend: 'pdf', text: 'PDF' },
        { extend: 'print', text: 'Imprimer' }
      ]
    });
  });
</script>
</body>
</html>
<?php $pdo = null; ?>
