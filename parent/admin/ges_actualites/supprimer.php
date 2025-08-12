<?php
session_start();


// Connexion à la base de données
require_once("../../../config.php");

// Vérifier si un ID est passé en GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Préparer la requête SQL
        $stmt = $pdo->prepare("DELETE FROM actualites WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirection avec succès
            header("Location: index.php?success=suppression");
            exit;
        } else {
            // Redirection avec erreur
            header("Location: index.php?error=echec_suppression");
            exit;
        }
    } catch (PDOException $e) {
        // Gérer les erreurs SQL
        error_log("Erreur SQL suppression actualité : " . $e->getMessage());
        header("Location: index.php?error=exception");
        exit;
    }
} else {
    // ID non valide
    header("Location: index.php?error=id_invalide");
    exit;
}
