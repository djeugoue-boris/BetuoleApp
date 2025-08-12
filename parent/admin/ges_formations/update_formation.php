<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $oldImage = $_POST['old_image'] ?? null;

    $newImagePath = $oldImage;

    // Gestion de l'image : si une nouvelle image est envoyée, on remplace l'ancienne
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $imageName;

        // Déplacer le fichier uploadé
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $newImagePath = $targetFile;

            // Supprimer l'ancienne image si elle existe et que ce n'est pas la même
            if ($oldImage && file_exists($oldImage) && $oldImage !== $newImagePath) {
                unlink($oldImage);
            }
        } else {
            // Gérer l'erreur d'upload
            header("Location: index.php?error=Erreur lors de l'upload de l'image");
            exit;
        }
    }

    // Préparation et exécution de la mise à jour
    $stmt = $pdo->prepare("UPDATE formations SET nom = ?, description = ?, prix = ?, quantite = ?, image = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $prix, $quantite, $newImagePath, $id]);

    // Redirection après succès
    header("Location: index.php?success=Formation modifiée avec succès");
    exit;
} else {
    // Si on accède à ce fichier sans POST, on bloque
    http_response_code(405);
    echo "Méthode non autorisée";
    exit;
}
?>
