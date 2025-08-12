<?php
// restore_annee.php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Récupérer l'archive
    $stmt = $pdo->prepare("SELECT annee, donnees FROM archives WHERE id = ?");
    $stmt->execute([$id]);
    $archive = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$archive) {
        header('Location: index.php?error=archive-introuvable');
        exit;
    }
    $donnees = json_decode($archive['donnees'], true);
    if (!$donnees) {
        header('Location: index.php?error=donnees-invalides');
        exit;
    }
    // Archiver l'année active
    $pdo->exec("UPDATE annees SET statut = 'archive' WHERE statut = 'active'");
    // Restaurer l'année
    $pdo->exec("UPDATE annees SET statut = 'active' WHERE annee = '" . addslashes($archive['annee']) . "'");
    // Vider les tables dépendantes
    foreach (['formations', 'paiements', 'actualites', 'contact_visiteurs'] as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`");
    }
    $pdo->exec("DELETE FROM utilisateurs WHERE role != 'admin'");
    // Restaurer les données
    foreach ($donnees as $table => $rows) {
        if ($table === 'utilisateurs') {
            foreach ($rows as $row) {
                $cols = array_keys($row);
                $vals = array_map(function($v) use ($pdo) { return $pdo->quote($v); }, array_values($row));
                $pdo->exec("INSERT INTO utilisateurs (".implode(',', $cols).") VALUES (".implode(',', $vals).")");
            }
        } else {
            foreach ($rows as $row) {
                $cols = array_keys($row);
                $vals = array_map(function($v) use ($pdo) { return $pdo->quote($v); }, array_values($row));
                $pdo->exec("INSERT INTO `$table` (".implode(',', $cols).") VALUES (".implode(',', $vals).")");
            }
        }
    }
    header('Location: index.php?success=restauration-ok');
    exit;
}
header('Location: index.php?error=parametre');
exit;
