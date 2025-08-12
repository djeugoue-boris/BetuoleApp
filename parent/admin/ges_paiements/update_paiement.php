<?php
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $id = (int) $_POST['id'];
    $montant = (float) $_POST['montant'];
    $mode_paiement = trim($_POST['mode_paiement']);
    $statut = trim($_POST['statut']);

    // Vérifie que tous les champs sont remplis
    if ($id && $montant && $mode_paiement && $statut) {
        // Prépare et exécute la requête de mise à jour
        $stmt = $pdo->prepare("UPDATE paiements SET montant = ?, mode_paiement = ?, statut = ? WHERE id = ?");
        $success = $stmt->execute([$montant, $mode_paiement, $statut, $id]);

        if ($success) {
            header("Location: index.php?success=paiement-modifie");
            exit;
        } else {
            header("Location: index.php?error=modif-echouee");
            exit;
        }
    } else {
        header("Location: index.php?error=champs-invalides");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
