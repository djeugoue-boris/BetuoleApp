<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Supprimer l’image associée si elle existe
    $stmtImg = $pdo->prepare("SELECT image FROM formations WHERE id = ?");
    $stmtImg->execute([$id]);
    $img = $stmtImg->fetchColumn();
    if ($img && file_exists($img)) {
        unlink($img);
    }

    // Supprimer la formation
    $stmt = $pdo->prepare("DELETE FROM formations WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php?success=suppression");
    exit;
} else {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}
