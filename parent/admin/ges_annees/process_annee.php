<?php
// process_annee.php
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nouvelle_annee'])) {
    $annee = trim($_POST['annee']);
    if (!$annee) {
        header('Location: index.php?error=annee-vide');
        exit;
    }

    // 1. Archiver toutes les données (hors admins)
    $tables = ['utilisateurs', 'formations', 'paiements', 'actualites', 'contact_visiteurs'];
    $archiveData = [];
    foreach ($tables as $table) {
        if ($table === 'utilisateurs') {
            // Archiver uniquement les non-admins
            $rows = $pdo->query("SELECT * FROM utilisateurs WHERE role != 'admin'")->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $rows = $pdo->query("SELECT * FROM `$table`") ->fetchAll(PDO::FETCH_ASSOC);
        }
        $archiveData[$table] = $rows;
    }
    $archiveJson = json_encode($archiveData);
    $stmt = $pdo->prepare("INSERT INTO archives (annee, donnees, date_archive) VALUES (?, ?, NOW())");
    $stmt->execute([$annee, $archiveJson]);

    // 2. Archiver l'année active
    $pdo->exec("UPDATE annees SET statut = 'archive' WHERE statut = 'active'");

    // 3. Créer la nouvelle année
    $stmt = $pdo->prepare("INSERT INTO annees (annee, statut) VALUES (?, 'active')");
    $stmt->execute([$annee]);

    // 4. Vider les tables (hors admins)
    // 1. Vider les tables dépendantes d'abord
    foreach (['formations', 'paiements', 'actualites', 'contact_visiteurs'] as $table) {
        $pdo->exec("TRUNCATE TABLE `$table`");
    }
    // 2. Supprimer uniquement les non-admins dans utilisateurs
    $pdo->exec("DELETE FROM utilisateurs WHERE role != 'admin'");

    // 5. Les administrateurs restent (rien à faire)

    header('Location: index.php?success=annee-creee');
    exit;
}
header('Location: index.php');
exit;
