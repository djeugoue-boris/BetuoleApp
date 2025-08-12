<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);

    $imagePath = null;

    // Gestion de l’image
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO formations (nom, description, prix, quantite, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $prix, $quantite, $imagePath]);

    header("Location: index.php?success=ajout");
    exit;
}
?>
