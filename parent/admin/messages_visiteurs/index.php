<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Suppression d'un message visiteur
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $stmtDel = $pdo->prepare("DELETE FROM contact_visiteurs WHERE id = ?");
    $stmtDel->execute([$delete_id]);
    echo '<div class="alert alert-success">Message supprimé avec succès.</div>';
}
// Récupération des messages visiteurs
$messages = $pdo->query("SELECT * FROM contact_visiteurs ORDER BY date_envoi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Messages visiteurs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" />
</head>
<body>
<main class="content" role="main" tabindex="-1" aria-live="polite">
  <div class="container-fluid">
    <h4 class="mb-4">📧 Messages des visiteurs</h4>
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
              <a href="?delete_id=<?= $msg['id'] ?>" onclick="return confirm('Supprimer ce message ?')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<!-- DataTables et boutons d'export -->
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
<script src="https://kit.fontawesome.com/2b6e1b1a2a.js" crossorigin="anonymous"></script>
<script>
  $(document).ready(function() {
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
</body>
</html>
<?php $pdo = null; ?>
