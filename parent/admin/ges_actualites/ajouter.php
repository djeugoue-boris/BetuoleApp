<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $imagePath = null;

    // Gestion image
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO actualites (titre, description, image, date_publication) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$titre, $description, $imagePath]);

    header("Location: index.php?success=ajout");
    if (!$stmt){
        header("Location: index.php?error=1");

    }
    exit;
}
?>
