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


// Action de modification rapide du statut
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['valider', 'rejeter'])) {
        $nouveau_statut = ($action === 'valider') ? 'validé' : 'rejeté';
        $stmt = $pdo->prepare("UPDATE paiements SET statut = ? WHERE id = ?");
        $stmt->execute([$nouveau_statut, $id]);

        header("Location: index.php?success=statut-modifie");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gestion des Paiements</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
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

  /* Style amélioré pour le tableau des paiements */
  #paiementsTable {
    background: var(--bg);
    color: var(--text);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(0,0,0,0.10);
    font-size: 1.08rem;
    margin-bottom: 2rem;
  }
  #paiementsTable thead {
    background: linear-gradient(90deg, #b30000 0%, #6f42c1 100%);
    color: #fff;
    font-size: 1.12rem;
    letter-spacing: 1px;
  }
  #paiementsTable th, #paiementsTable td {
    vertical-align: middle !important;
    padding: 0.85rem 1.1rem;
    border: none;
  }
  #paiementsTable tbody tr {
    transition: background 0.2s, box-shadow 0.2s;
  }
  #paiementsTable tbody tr:hover {
    background: #f5e6ff !important;
    box-shadow: 0 2px 12px rgba(111,66,193,0.10);
  }
  #paiementsTable .badge {
    font-size: 0.98em;
    padding: 0.45em 1em;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
  }
  #paiementsTable .btn {
    border-radius: 8px;
    font-size: 1em;
    padding: 0.35em 0.7em;
    margin: 0 2px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    transition: background 0.18s, color 0.18s;
  }
  #paiementsTable .btn-warning {
    color: #fff;
    background: linear-gradient(90deg,#ffc107,#b30000);
    border: none;
  }
  #paiementsTable .btn-warning:hover {
    background: linear-gradient(90deg,#b30000,#ffc107);
    color: #fff;
  }
  #paiementsTable .btn-danger {
    background: #b30000;
    color: #fff;
    border: none;
  }
  #paiementsTable .btn-danger:hover {
    background: #ff3333;
    color: #fff;
  }
  #paiementsTable .btn-success {
    background: #198754;
    color: #fff;
    border: none;
  }
  #paiementsTable .btn-success:hover {
    background: #146c43;
    color: #fff;
  }
  @media (max-width: 600px) {
    #paiementsTable th, #paiementsTable td {
      padding: 0.5rem 0.4rem;
      font-size: 0.97em;
    }
  }

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
  <a href="#" class="active" aria-current="page"><i class="fa-solid fa-money-bill-wave"></i><span> Paiements</span></a>
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
<?php
require_once '../../../config.php';
ob_start();
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Récupération des paiements + utilisateurs
$sql = "SELECT p.*, u.nom, u.prenom FROM paiements p 
        JOIN utilisateurs u ON p.id_utilisateur = u.id
        ORDER BY date_paiement DESC";
$paiements = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


// Suppression d’un paiement si delete_id est présent
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    
    // Préparer et exécuter la suppression
    $stmt = $pdo->prepare("DELETE FROM paiements WHERE id = ?");
    $success = $stmt->execute([$id]);

    // Redirection avec message dans URL pour feedback utilisateur
    if ($success) {
        echo("Bravo, suppression reussie! <a href='index.php' style='color:green; font-weight: bold;'>Continuer maintenant</a>");
    } else {
        header("Location: index.php?error=suppression-echouee");
    }
    exit;
}
?>


  <div class="d-flex justify-content-between align-items-center mb-3">
      <h4><i class="fa-solid fa-money-bill-wave"></i> Paiements enregistrés</h4>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus-circle me-1"></i> Nouveau Paiement
      </button>
    </div>

 
    <table id="paiementsTable" class="table table-bordered table-hover align-middle" style="min-width:1100px; width:100%;">
      <thead class="table-dark">
        <tr>
          <th>Apprenant</th>
          <th>Montant (FCFA)</th>
          <th>Mode</th>
          <th>Statut</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($paiements as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?></td>
            <td><?= number_format($p['montant'], 0, ',', ' ') ?></td>
            <td><?= htmlspecialchars($p['mode_paiement'] ?? '---') ?></td>
            <td>
              <?php if ($p['statut'] == 'validé'): ?>
                <span class="badge bg-success">Validé</span>
              <?php elseif ($p['statut'] == 'rejeté'): ?>
                <span class="badge bg-danger">Rejeté</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">En attente</span>
              <?php endif; ?>

              <div class="mt-1">
                <a href="?action=valider&id=<?= $p['id'] ?>" class="btn btn-sm btn-success btn-sm me-1">✔</a>
                <a href="?action=rejeter&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger btn-sm">✖</a>
              </div>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($p['date_paiement'])) ?></td>
            <td>
              <button type="button" class="btn btn-sm btn-warning edit-btn"
                data-id="<?= $p['id'] ?>"
                data-montant="<?= $p['montant'] ?>"
                data-mode="<?= htmlspecialchars($p['mode_paiement']) ?>"
                data-statut="<?= $p['statut'] ?>">
                <i class="fas fa-edit"></i>
              </button>

              <a href="?delete_id=<?= $p['id'] ?>" onclick="return confirm('Supprimer ce paiement ?')" class="btn btn-sm btn-danger">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Section Messages des visiteurs -->
  <div class="container-fluid mt-5">
    <h4 class="mb-4">📧 Messages des visiteurs</h4>
    <?php
    // Suppression d'un message visiteur
    if (isset($_GET['delete_msg_id'])) {
        $delete_id = (int) $_GET['delete_msg_id'];
        $stmtDel = $pdo->prepare("DELETE FROM contact_visiteurs WHERE id = ?");
        $stmtDel->execute([$delete_id]);
        echo '<div class="alert alert-success">Message supprimé avec succès.</div>';
    }
    // Récupération des messages visiteurs
    $messages = $pdo->query("SELECT * FROM contact_visiteurs ORDER BY date_envoi DESC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table id="messagesTable" class="table table-bordered table-hover table-striped table-responsive nowrap align-middle" style="width:100%">
      <thead class="table-dark">
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Objet</th>
          <th>Message</th>
          <th>Date d'envoi</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
          <tr>
            <td><?= htmlspecialchars($msg['nom']) ?></td>
            <td><?= htmlspecialchars($msg['email']) ?></td>
            <td><?= htmlspecialchars($msg['objet']) ?></td>
            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
            <td><?= htmlspecialchars($msg['date_envoi']) ?></td>
            <td>
              <a href="?delete_msg_id=<?= $msg['id'] ?>" onclick="return confirm('Supprimer ce message ?')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<!-- modal add paiements  -->

<!-- Modal ajout -->
<div class="modal fade text-dark" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="add_paiement.php" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Ajouter un Paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Apprenant</label>
          <select name="id_utilisateur" class="form-select" required>
            <option value="">-- Choisir un apprenant --</option>
            <?php
            $apprenants = $pdo->query("SELECT id, nom, prenom FROM utilisateurs WHERE role = 'apprenant'")->fetchAll();
            foreach ($apprenants as $a) {
              echo "<option value='{$a['id']}'>" . htmlspecialchars($a['nom'] . ' ' . $a['prenom']) . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="mb-2">
          <label>Montant</label>
          <input type="number" step="0.01" class="form-control" name="montant" required>
        </div>
        <div class="mb-2">
          <label>Mode de paiement</label>
          <input type="text" class="form-control" name="mode_paiement" required>
        </div>
        <div class="mb-2">
          <label>Statut</label>
            <select name="statut" class="form-select" required>
              <option value="validé">Validé</option>
              <option value="en attente">En attente</option>
              <option value="rejeté">Rejeté</option>
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


<!-- Modal édition -->
<div class="modal fade text-dark" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="update_paiement.php" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Modifier un Paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <div class="mb-2">
          <label>Montant</label>
          <input type="number" step="0.01" class="form-control" name="montant" id="edit-montant" required>
        </div>
        <div class="mb-2">
          <label>Mode de paiement</label>
          <input type="text" class="form-control" name="mode_paiement" id="edit-mode" required>
        </div>
        <div class="mb-2">
          <label>Statut</label>
            <select name="statut" id="edit-statut" class="form-select" required>
              <option value="validé">Validé</option>
              <option value="en attente">En attente</option>
              <option value="rejeté">Rejeté</option>
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


<script>
  // Correction : on utilise la délégation d'événement et on améliore la visibilité du bouton Modifier
  const editModal = new bootstrap.Modal(document.getElementById('editModal'));
  $('#paiementsTable').on('click', '.edit-btn', function() {
    document.getElementById('edit-id').value = $(this).data('id');
    document.getElementById('edit-montant').value = $(this).data('montant');
    document.getElementById('edit-mode').value = $(this).data('mode');
    document.getElementById('edit-statut').value = $(this).data('statut');
    editModal.show();
  });
</script>
<style>
  /* Style amélioré pour le bouton Modifier */
  #paiementsTable .btn-warning.edit-btn {
    background: linear-gradient(90deg,#ffb347,#ffcc33);
    color: #222;
    border: none;
    font-weight: bold;
    box-shadow: 0 1px 4px rgba(255,193,7,0.15);
    transition: background 0.18s, color 0.18s;
  }
  #paiementsTable .btn-warning.edit-btn:hover {
    background: linear-gradient(90deg,#ffcc33,#ffb347);
    color: #111;
  }
</style>

<?php if (isset($_GET['success']) && $_GET['success'] == 'paiement-modifie'): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Paiement modifié',
    text: 'Les informations du paiement ont été mises à jour.',
    confirmButtonColor: '#198754',
    timer: 3000
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Erreur',
    text: '<?= ($_GET['error'] === 'modif-echouee') ? 'La modification a échoué.' : 'Champs invalides' ?>',
    confirmButtonColor: '#dc3545'
  });
</script>
<?php endif; ?>

<?php $pdo = null; ?>


</main>
<!-- modal de editmodal -->
<div class="modal fade text-dark" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="update_paiement.php" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Modifier un Paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <div class="mb-2">
          <label>Montant</label>
          <input type="number" step="0.01" class="form-control" name="montant" id="edit-montant" required>
        </div>
        <div class="mb-2">
          <label>Mode de paiement</label>
          <input type="text" class="form-control" name="mode_paiement" id="edit-mode" required>
        </div>
        <div class="mb-2">
          <label>Statut</label>
          <select name="statut" id="edit-statut" class="form-select" required>
            <option value="validé">Validé</option>
            <option value="en attente">En attente</option>
            <option value="rejeté">Rejeté</option>
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
  $(document).ready(function() {
    $('#paiementsTable').DataTable({
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

    $('#messagesTable').DataTable({
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



</script>

<script>
  // Script pour le modal d'édition
  // Utilisation de Bootstrap 5 pour les modals
  document.addEventListener('DOMContentLoaded', function () {
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.getElementById('edit-id').value = this.dataset.id;
        document.getElementById('edit-montant').value = this.dataset.montant;
        document.getElementById('edit-mode').value = this.dataset.mode;
        document.getElementById('edit-statut').value = this.dataset.statut;
        editModal.show();
      });
    });
  });
</script>

</body>
</html>
<?php $pdo = null; ?>


