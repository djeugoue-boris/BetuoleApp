<?php
// test_connection.php
// Test de la connexion à la base de données et récupération des actualités et statistiques

require_once '../../../config.php';

function testDatabaseConnection() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connexion à la base de données réussie.\n";

        // Test récupération actualités
        $actualites = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC")->fetchAll(PDO::FETCH_ASSOC);
        if (is_array($actualites)) {
            echo "Récupération des actualités réussie. Nombre d'actualités : " . count($actualites) . "\n";
        } else {
            echo "Erreur lors de la récupération des actualités.\n";
        }

        // Test récupération statistiques
        $stats = [
            'admins' => $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'")->fetchColumn(),
            'apprenants' => $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'apprenant'")->fetchColumn(),
            'formations' => $pdo->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
            'paiements' => $pdo->query("SELECT COUNT(*) FROM paiements")->fetchColumn(),
            'actualites' => $pdo->query("SELECT COUNT(*) FROM actualites")->fetchColumn(),
            'annee_active' => $pdo->query("SELECT annee FROM annees WHERE statut = 'active' LIMIT 1")->fetchColumn() ?: 'Aucune',
            'archives' => $pdo->query("SELECT COUNT(*) FROM archives")->fetchColumn(),
            'annees_archivees' => $pdo->query("SELECT COUNT(*) FROM annees WHERE statut = 'archive'")->fetchColumn(),
        ];

        echo "Récupération des statistiques réussie :\n";
        print_r($stats);

    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage() . "\n";
    }
}

testDatabaseConnection();
?>
