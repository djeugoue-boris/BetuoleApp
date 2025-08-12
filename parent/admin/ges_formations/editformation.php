<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 0);
    $old_image = $_POST['old_image'] ?? null;
    $imagePath = $old_image;

    // Gestion de la nouvelle image
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;

            // Supprimer l'ancienne image si différente et existe
            if ($old_image && file_exists($old_image) && $old_image !== $imagePath) {
                unlink($old_image);
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE formations SET nom = ?, description = ?, prix = ?, quantite = ?, image = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $prix, $quantite, $imagePath, $id]);

    // Redirection vers index avec message succès
    header("Location: index.php?success=modification");
    exit;
} else {
    // Accès direct interdit
    header("HTTP/1.1 405 Method Not Allowed");
    exit("Méthode non autorisée.");
}
