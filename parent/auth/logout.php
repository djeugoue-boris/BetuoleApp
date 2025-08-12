<?php
// logout.php
session_start();
// Détruit toutes les données de session
$_SESSION = [];
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Déconnexion</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS (optionnel si déjà dans ton layout) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Déconnexion réussie',
      text: 'À bientôt sur Betuole Académie !',
      timer: 2000,
      showConfirmButton: false,
      willClose: () => {
        // Redirection une fois l'alerte fermée
        window.location.href = 'login.php';
      }
    });
  </script>
</body>
</html>
