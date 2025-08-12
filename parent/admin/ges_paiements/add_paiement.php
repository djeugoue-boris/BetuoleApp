<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si les données sont bien envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = $_POST['id_utilisateur'] ?? null;
    $montant = $_POST['montant'] ?? null;
    $mode_paiement = $_POST['mode_paiement'] ?? null;
    $statut = $_POST['statut'] ?? null;
    $type_paiement = $_POST['type_paiement'] ?? null;

    // Validation
    if (!$id_utilisateur || !$montant || !$mode_paiement || !$statut) {
        die("Veuillez remplir tous les champs.");
    }

    // Générer une référence unique si paiement mobile
    $reference = '';
    if (in_array($mode_paiement, ['MTN', 'MOMO'])) {
        $rand = random_int(1, 1999);
        $reference = 'BTT0' . str_pad($rand, 4, '0', STR_PAD_LEFT);
    }

    // Insertion
    $sql = "INSERT INTO paiements (id_utilisateur, montant, mode_paiement, statut, date_paiement, type_paiement, reference)
            VALUES (:id_utilisateur, :montant, :mode_paiement, :statut, NOW(), :type_paiement, :reference)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':mode_paiement', $mode_paiement);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':type_paiement', $type_paiement);
    $stmt->bindParam(':reference', $reference);

    if ($stmt->execute()) {
        header("Location: ../../apprenant/dashboard_app.php?success=paiement-ajoute");
        exit;   
    } else {
        header("Location: index.php?error=insertion-echouee");
    }
} else {
    die("Méthode non autorisée.");
}
?>
