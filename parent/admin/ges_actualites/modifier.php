<?php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $date_publication = $_POST['date_publication'];
    $statut = $_POST['statut'];
    $oldImage = $_POST['old_image'];

    $imagePath = $oldImage;

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $newName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
            if (file_exists($oldImage) && $oldImage != $imagePath) {
                unlink($oldImage);
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE actualites SET titre=?, description=?, image=?, date_publication=?, statut=? WHERE id=?");
    $stmt->execute([$titre, $description, $imagePath, $date_publication, $statut, $id]);

    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        header("Location: index.php?success=update");
    } else {
        header("Location: index.php?warning=nochange");
    }
    exit;
}
?>
