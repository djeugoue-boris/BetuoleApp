<?php
session_start();
require_once '../../config.php';

// Redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Connexion PDO avec try/catch
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($nom) || empty($prenom) || empty($email)) {
            $_SESSION['error'] = "Nom, prénom et email sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Email invalide.";
        } elseif ($password !== '' && $password !== $password_confirm) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        } else {
            // Vérifier unicité email
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
            $stmt->execute([$email, $userId]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = "Cet email est déjà utilisé.";
            } else {
                if ($password !== '') {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, email=?, telephone=?, mot_de_passe=? WHERE id=?");
                    $stmt->execute([$nom, $prenom, $email, $telephone, $hash, $userId]);
                } else {
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, email=?, telephone=? WHERE id=?");
                    $stmt->execute([$nom, $prenom, $email, $telephone, $userId]);
                }
                $_SESSION['success'] = "Profil mis à jour avec succès.";
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    elseif ($_POST['action'] === 'delete_account') {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$userId]);
        session_destroy();
        header('Location: ../auth/login.php');
        exit;
    }
    elseif ($_POST['action'] === 'choose_formation') {
        $chosen_filiere = $_POST['chosen_filiere'] ?? '';
        if ($chosen_filiere) {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET filiere = ? WHERE id = ?");
            if ($stmt->execute([$chosen_filiere, $userId])) {
                $_SESSION['success'] = "Formation choisie avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors du choix de la formation.";
            }
        } else {
            $_SESSION['error'] = "Veuillez choisir une formation.";
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

try {
    $stmt = $pdo->prepare("SELECT nom, prenom, email, telephone, matricule, role, filiere FROM utilisateurs WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch formation corresponding to user's filiere
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE nom = ?");
    $stmt->execute([$user['filiere']]);
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);

    // Calculate balance as formation price minus sum of validated payments
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) AS total_paiements FROM paiements WHERE id_utilisateur = ? AND statut = 'validé'");
    $stmt->execute([$userId]);
    $total_paiements = $stmt->fetchColumn();

    $solde = 0;
    if ($formation) {
        $solde = $formation['prix'] - $total_paiements;
        if ($solde < 0) {
            $solde = 0;
        }
    }

    // Historique paiements
    $stmt = $pdo->prepare("SELECT * FROM paiements WHERE id_utilisateur = ? ORDER BY date_paiement DESC");
    $stmt->execute([$userId]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur lors de la récupération des données utilisateur : " . $e->getMessage());
}

function showAlert($type, $message) {
    $icon = $type === 'success' ? 'success' : 'error';
    echo "<script>
        Swal.fire({
            icon: '$icon',
            title: '". ucfirst($type) ."',
            text: " . json_encode($message) . ",
            timer: 2500,
            showConfirmButton: false
        });
    </script>";
}
?>
<?php
// [Ton PHP identique ici]
// ... (Je pars du même PHP que la version précédente)
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profil & Paiements | Betuole Académie</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
  /* Reset */
  body, html {
    height: 100%;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: #f0f4f8;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  nav.navbar {
    background: rgba(10, 40, 80, 0.9);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  }
  nav.navbar .navbar-brand {
    font-weight: 700;
    font-size: 1.6rem;
    letter-spacing: 2px;
    color: #fff;
  }
  nav.navbar .nav-link {
    color: #d1d9e6;
    font-weight: 600;
    margin-right: 1.2rem;
    transition: color 0.3s ease;
  }
  nav.navbar .nav-link.active,
  nav.navbar .nav-link:hover {
    color: #50c0e9;
  }
  nav.navbar .btn-outline-light {
    font-weight: 600;
    border-color: #50c0e9;
    color: #50c0e9;
    transition: all 0.3s ease;
  }
  nav.navbar .btn-outline-light:hover {
    background-color: #50c0e9;
    color: #002b4d;
  }

  main {
    flex: 1;
    max-width: 960px;
    margin: 5rem auto 3rem;
    padding: 2.5rem 3rem;
    background: rgba(255 255 255 / 0.05);
    border-radius: 1rem;
    box-shadow:
      inset 0 0 30px 0 rgba(255 255 255 / 0.07),
      0 15px 40px rgba(0 0 0 / 0.4);
    backdrop-filter: blur(15px);
  }
  h1 {
    text-align: center;
    font-size: 2.4rem;
    margin-bottom: 2rem;
    color: #a2d2ff;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
  }
  section {
    margin-bottom: 3rem;
  }
  /* Profile section */
  .profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
  }
  .profile-img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    box-shadow:
      0 0 20px #50c0e9,
      0 0 40px #1b99d9;
    object-fit: cover;
    border: 4px solid #50c0e9;
  }
  .profile-info {
    flex: 1;
    min-width: 220px;
  }
  .profile-info h2 {
    margin-bottom: 0.4rem;
    font-weight: 700;
    font-size: 1.8rem;
    color: #cff4fc;
    text-shadow: 0 0 10px #50c0e9;
  }
  .profile-info p {
    font-size: 1.05rem;
    line-height: 1.5;
    color: #d3e9ffcc;
    margin-bottom: 0.3rem;
  }
  .profile-info strong {
    color: #b0dfff;
  }

  /* Formulaire */
  form {
    background: rgba(255 255 255 / 0.08);
    padding: 1.8rem 2rem;
    border-radius: 1rem;
    box-shadow: 0 0 15px rgba(80, 192, 233, 0.5);
  }
  label {
    font-weight: 600;
    color: #a3d5ff;
    margin-bottom: 0.4rem;
    display: block;
  }
  input.form-control, select.form-select {
    background: rgba(255 255 255 / 0.15);
    border: none;
    border-radius: 0.8rem;
    padding: 0.65rem 1rem;
    color: #d4e8ff;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.3s ease;
  }
  input.form-control:focus, select.form-select:focus {
    background: rgba(255 255 255 / 0.35);
    outline: none;
    box-shadow: 0 0 10px #50c0e9;
    color: #fff;
  }

  /* Enhanced style for payment mode dropdown */
  #mode_paiement {
    font-size: 1.15rem;
    font-weight: 700;
    background: linear-gradient(135deg, #3a5a7c, #2e4a66);
    color: #fff;
    border: 2px solid #2e4a66;
    box-shadow: 0 0 8px #3a5a7c99;
    padding: 0.75rem 1.2rem;
    border-radius: 1rem;
    transition: background 0.4s ease, box-shadow 0.4s ease, color 0.4s ease;
    cursor: pointer;
  }
  #mode_paiement:hover {
    background: linear-gradient(135deg, #2e4a66, #3a5a7c);
    box-shadow: 0 0 12px #2e4a6699;
    color: #e0e8f0;
  }
  #mode_paiement:focus {
    background: linear-gradient(135deg, #2e4a66, #3a5a7c);
    box-shadow: 0 0 18px #2e4a6699;
    outline: none;
    color: #e0e8f0;
  }

  /* Style options inside the payment mode dropdown */
  #mode_paiement option {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2a3b52;
    background-color: #d0d8e8;
    padding: 0.5rem 1rem;
  }
  #mode_paiement option:hover {
    background-color: #aab8d3;
    color: #1a2a44;
  }
  #mode_paiement option:checked {
    background-color: #2e4a66;
    color: #fff;
  }

  /* Enhanced style for type of payment dropdown */
  #type_paiement {
    font-size: 1.15rem;
    font-weight: 700;
    background: linear-gradient(135deg, #3a5a7c, #2e4a66);
    color: #fff;
    border: 2px solid #2e4a66;
    box-shadow: 0 0 8px #3a5a7c99;
    padding: 0.75rem 1.2rem;
    border-radius: 1rem;
    transition: background 0.4s ease, box-shadow 0.4s ease, color 0.4s ease;
    cursor: pointer;
  }
  #type_paiement:hover {
    background: linear-gradient(135deg, #2e4a66, #3a5a7c);
    box-shadow: 0 0 12px #2e4a6699;
    color: #e0e8f0;
  }
  #type_paiement:focus {
    background: linear-gradient(135deg, #2e4a66, #3a5a7c);
    box-shadow: 0 0 18px #2e4a6699;
    outline: none;
    color: #e0e8f0;
  }

  /* Style options inside the type of payment dropdown */
  #type_paiement option {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2a3b52;
    background-color: #d0d8e8;
    padding: 0.5rem 1rem;
  }
  #type_paiement option:hover {
    background-color: #aab8d3;
    color: #1a2a44;
  }
  #type_paiement option:checked {
    background-color: #2e4a66;
    color: #fff;
  }

  /* Style options inside the payment mode dropdown */
  #mode_paiement option {
    font-size: 1.2rem;
    font-weight: 700;
    color: #003366;
    background-color: #cce6ff;
    padding: 0.5rem 1rem;
  }
  #mode_paiement option:hover {
    background-color: #99ccff;
    color: #001a33;
  }
  #mode_paiement option:checked {
    background-color: #1b99d9;
    color: #fff;
  }

  /* Boutons */
  button.btn-primary {
    background: linear-gradient(45deg, #4bc0eb, #2a91d1);
    border: none;
    font-weight: 700;
    padding: 0.9rem 1.4rem;
    font-size: 1.15rem;
    border-radius: 1rem;
    box-shadow: 0 4px 15px #3c8cd2cc;
    transition: all 0.3s ease;
  }
  button.btn-primary:hover {
    background: linear-gradient(45deg, #2a91d1, #1e5a8a);
    box-shadow: 0 6px 22px #1a5c92cc;
    transform: translateY(-2px);
    cursor: pointer;
  }
  button.btn-outline-danger {
    color: #ff6b6b;
    border-color: #ff6b6b;
    font-weight: 700;
    border-radius: 1rem;
    transition: all 0.3s ease;
  }
  button.btn-outline-danger:hover {
    background-color: #ff6b6b;
    color: #fff;
    box-shadow: 0 6px 20px #ff6b6bcc;
  }

  /* Tableau historique */
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 1rem;
    color: #cde6fe;
  }
  thead tr {
    background: #155084;
  }
  thead tr th {
    padding: 12px 20px;
    text-align: left;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
  }
  tbody tr {
    background: rgba(255 255 255 / 0.06);
    transition: background-color 0.3s ease;
  }
  tbody tr:nth-child(odd) {
    background: rgba(255 255 255 / 0.03);
  }
  tbody tr:hover {
    background: rgba(80 192 233 / 0.2);
  }
  tbody td {
    padding: 14px 18px;
    vertical-align: middle;
  }

  /* Badge statut */
  .badge-status {
    font-weight: 600;
    padding: 0.4em 0.9em;
    border-radius: 20px;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.08em;
  }
  .badge-valid {
    background: #28a745;
    box-shadow: 0 0 8px #28a745cc;
    color: #fff;
  }
  .badge-pending {
    background: #ffc107;
    box-shadow: 0 0 8px #ffc107cc;
    color: #222;
  }
  .badge-other {
    background: #6c757d;
    color: #fff;
  }

  /* Modal */
  .modal-content {
    background: linear-gradient(145deg, #0e1c3f, #1a315f);
    color: #c0d9ff;
    border-radius: 1rem;
    border: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.7);
  }
  .modal-header {
    border-bottom: 2px solid #2a5298;
    font-weight: 700;
    font-size: 1.4rem;
  }
  .btn-close {
    filter: invert(100%);
  }
  .modal-footer {
    border-top: 2px solid #2a5298;
  }
  .form-control.is-invalid {
    border-color: #ff6b6b;
    box-shadow: 0 0 10px #ff6b6bcc;
  }
  .invalid-feedback {
    color: #ff6b6b;
    font-weight: 600;
  }

  /* Responsive */
  @media (max-width: 576px) {
    main {
      margin: 4rem 1rem 2rem;
      padding: 1.8rem 1.6rem;
    }
    .profile-header {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
    .profile-info {
      min-width: auto;
    }
  }

  /* Scrollbar personnalisé */
  ::-webkit-scrollbar {
    width: 9px;
  }
  ::-webkit-scrollbar-track {
    background: #12294f;
  }
  ::-webkit-scrollbar-thumb {
    background: #50c0e9;
    border-radius: 10px;
  }

  /* Style personnalisé pour la liste déroulante des formations */
  .custom-formation-select {
    background: #fff !important;
    color: #003366 !important;
    font-weight: bold;
    font-size: 1.15rem;
    border: 2px solid #50c0e9;
    box-shadow: 0 2px 8px #50c0e955;
    border-radius: 0.7rem;
    padding: 0.7rem 1.1rem;
    margin-bottom: 0.5rem;
    transition: border-color 0.3s, box-shadow 0.3s;
  }
  .custom-formation-select:focus {
    border-color: #1b99d9;
    box-shadow: 0 0 0 3px #50c0e933;
    outline: none;
    background: #f0f8ff;
    color: #003366;
  }
  .custom-formation-select option {
    background: #e6f7ff;
    color: #003366;
    font-weight: 600;
    padding: 0.7rem 1rem;
    font-size: 1.1rem;
  }
  .custom-formation-select option:checked {
    background: #50c0e9;
    color: #fff;
  }
</style>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a href="../../index.php" class="navbar-brand"><i class="bi bi-mortarboard-fill me-2"></i>Betuole Académie</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a href="../../index.php" class="nav-link">Accueil</a></li>
        <li class="nav-item"><a href="../../apropos.php" class="nav-link">À propos</a></li>
        <li class="nav-item"><a href="../../actualites.php" class="nav-link">Actualités</a></li>
        <li class="nav-item"><a href="../../contact.php" class="nav-link">Contact</a></li>
        <li class="nav-item"><a href="../admin/ges_formations/catalogue.php" class="nav-link ">Formations</a></li>
        <li class="nav-item"><a href="#" class="nav-link active">Dashboard </a></li>
      </ul>
      <a href="../auth/logout.php" class="btn btn-outline-light ms-lg-4 fw-semibold"><i class="bi bi-box-arrow-right me-1"></i>Déconnexion</a>
    </div>
  </div>
</nav>

<main>
  <h1>Bienvenue, <?= htmlspecialchars($user['prenom']) ?> !</h1>

  <section class="profile-header">
    <img src="../../img/logo.jpg" alt="Photo profil" class="profile-img" />
    <div class="profile-info">
      <h2><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></h2>
      <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></p>
      <p><strong>Matricule :</strong> <?= htmlspecialchars($user['matricule']) ?></p>
      <p><strong>Rôle :</strong> <?= ucfirst(htmlspecialchars($user['role'])) ?></p>
      <?php if ($formation): ?>
        <p><strong>Formation choisie :</strong> <?= htmlspecialchars($formation['nom']) ?></p>
        <p><strong>Prix formation :</strong> <?= number_format($formation['prix'], 2, ',', ' ') ?> FCFA</p>
      <?php else: ?>
        <p><em>Aucune formation choisie</em></p>
      <?php endif; ?>
      <p><strong>Solde actuel :</strong> <?= number_format($solde, 2, ',', ' ') ?> FCFA</p>
      <?php if ($solde > 0 && $total_paiements == 0): ?>
        <p><em>Note: Le solde sera mis à jour après validation du paiement par l'administrateur.</em></p>
      <?php endif; ?>
    </div>
  </section>

  <section>
    <h3 class="mb-3">Enregistrer un paiement</h3>
    <form method="POST" action="../admin/ges_paiements/add_paiement.php" class="row g-4">
      <input type="hidden" name="id_utilisateur" value="<?= $userId ?>" />
      <div class="col-md-4">
        <label for="type_paiement">Type de paiement</label>
        <select id="type_paiement" name="type_paiement" class="form-select" required>
          <option value="" disabled selected>Choisir un type</option>
          <option value="Frais d'inscription">Frais d'inscription</option>
          <option value="Frais de scolarité">Frais de scolarité</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="montant">Montant (FCFA)</label>
        <input type="number" step="0.01" id="montant" name="montant" class="form-control" min="0.01" required />
      </div>
      <div class="col-md-4">
        <label for="mode_paiement">Mode de paiement</label>
        <select id="mode_paiement" name="mode_paiement" class="form-select" required>
          <option value="" disabled selected>Choisir un mode</option>
          <option value="MTN">MTN</option>
          <option value="MOMO">MOMO</option>
          <option value="Espèces">Espèces</option>
          <option value="Autre">Autre</option>
        </select>
      </div>
      <input type="hidden" name="statut" value="en attente" />
      <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
      </div>
    </form>
  </section>

  <section>
    <h3 class="mb-3">Historique des paiements</h3>
    <div class="table-responsive rounded shadow-sm">
      <table>
        <thead>
          <tr>
            <th>Montant (FCFA)</th>
            <th>Mode</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Reçu</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($payments) === 0): ?>
            <tr><td colspan="5" class="text-center">Aucun paiement enregistré.</td></tr>
          <?php else: ?>
            <?php foreach ($payments as $p): ?>
              <tr>
                <td><?= number_format($p['montant'], 2, ',', ' ') ?></td>
                <td><?= htmlspecialchars($p['mode_paiement']) ?></td>
                <td>
                  <?php
                  if ($p['statut'] === 'validé') {
                    echo '<span class="badge-status badge-valid">Validé</span>';
                  } elseif ($p['statut'] === 'en attente') {
                    echo '<span class="badge-status badge-pending">En attente</span>';
                  } else {
                    echo '<span class="badge-status badge-other">Inconnu</span>';
                  }
                  ?>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($p['date_paiement'])) ?></td>
                <td>
                  <?php if ($p['statut'] === 'validé'): ?>
                    <a href="../admin/ges_paiements/generate_receipt.php?id=<?= $p['id'] ?>" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener">Télécharger</a>
                  <?php else: ?>
                    ---
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  </section>

  <div class="d-flex justify-content-center justify-content-lg-start gap-3 flex-wrap mt-4">
    <button class="btn btn-primary px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
      <i class="bi bi-pencil-square me-2"></i>Modifier le profil
    </button>
    <?php if (!$formation): ?>
    <button class="btn btn-info px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#chooseFormationModal" style="background: linear-gradient(45deg, #17a2b8, #117a8b); border: none; box-shadow: 0 4px 15px #117a8bcc;">
      <i class="bi bi-journal-text me-2"></i>Choisir une formation
    </button>
    <?php endif; ?>
    <button class="btn btn-danger px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
      <i class="bi bi-trash me-2"></i>Supprimer le compte
    </button>
  </div>
</main>

<!-- Modals: identiques, avec style sombre -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="modal-content needs-validation" novalidate>
      <input type="hidden" name="action" value="update_profile" />
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Modifier mon profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="prenom" class="form-label">Prénom</label>
          <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required />
          <div class="invalid-feedback">Veuillez renseigner votre prénom.</div>
        </div>
        <div class="mb-3">
          <label for="nom" class="form-label">Nom</label>
          <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required />
          <div class="invalid-feedback">Veuillez renseigner votre nom.</div>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
          <div class="invalid-feedback">Veuillez renseigner un email valide.</div>
        </div>
        <div class="mb-3">
          <label for="telephone" class="form-label">Téléphone</label>
          <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="modal-content needs-validation" novalidate>
      <input type="hidden" name="action" value="delete_account" />
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAccountModalLabel">Supprimer mon compte</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger fw-bold">Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-outline-danger" name="confirm_delete" value="1">Supprimer</button>
      </div>
    </form>
  </div>
</div>

<footer class="text-center text-white mb-4" style="font-size: 0.9rem; opacity: 0.6;">
  &copy; <?= date('Y') ?> Betuole Académie - Tous droits réservés
</footer>

<!-- Bootstrap + SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  })();

  <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
      icon: 'success',
      title: 'Succès',
      text: <?= json_encode($_SESSION['success']) ?>,
      timer: 3000,
      showConfirmButton: false,
    });
  <?php unset($_SESSION['success']); endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    Swal.fire({
      icon: 'error',
      title: 'Erreur',
      text: <?= json_encode($_SESSION['error']) ?>,
      showConfirmButton: true,
    });
  <?php unset($_SESSION['error']); endif; ?>
</script>

<!-- Add modal for choosing formation -->
<?php if (!$formation): ?>
<div class="modal fade" id="chooseFormationModal" tabindex="-1" aria-labelledby="chooseFormationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="modal-content needs-validation" novalidate>
      <input type="hidden" name="action" value="choose_formation" />
      <div class="modal-header">
        <h5 class="modal-title" id="chooseFormationModalLabel">Choisir une formation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="chosen_filiere" class="form-label">Formation</label>
          <select id="chosen_filiere" name="chosen_filiere" class="form-select custom-formation-select" required style="background: #fff; color: #003366; font-weight: bold; font-size: 1.15rem; border: 2px solid #50c0e9; box-shadow: 0 2px 8px #50c0e955;">
            <option value="" disabled selected style="color: #888; font-style: italic;">Choisir une formation</option>
            <?php
            $stmt = $pdo->query("SELECT nom FROM formations ORDER BY nom ASC");
            $formations_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($formations_list as $f) {
                echo '<option value="' . htmlspecialchars($f['nom']) . '" style="background: #e6f7ff; color: #003366; font-weight: 600; padding: 0.7rem 1rem;">' . htmlspecialchars($f['nom']) . '</option>';
            }
            ?>
          </select>
          <div class="invalid-feedback">Veuillez choisir une formation.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Valider</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>
