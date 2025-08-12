<?php
require_once '../../../config.php'; // adapte le chemin selon ton arborescence

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo("Bravo, suppression reussie! <a href='index.php' style='color:green; font-weight: bold;'>Continuer maintenant</a>");
    } else {
        header("Location: index.php?error=suppression-echouee");
    }
    exit;
} else {
    header("Location: index.php");
    exit;
}
