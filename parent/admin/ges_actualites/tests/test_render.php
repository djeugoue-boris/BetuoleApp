<?php
// test_render.php
// Test simple du rendu HTML de la page index.php pour vérifier la présence des actualités

// Activer le buffer de sortie
ob_start();

// Inclure la page index.php
include '../index.php';

// Récupérer le contenu HTML généré
$html = ob_get_clean();

// Vérifier la présence d'au moins un titre d'actualité dans le HTML
// Pour cela, on récupère les titres depuis la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $actualites = $pdo->query("SELECT titre FROM actualites ORDER BY date_publication DESC")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$allFound = true;
foreach ($actualites as $titre) {
    if (strpos($html, htmlspecialchars($titre)) === false) {
        echo "Titre non trouvé dans le rendu HTML : " . $titre . "\n";
        $allFound = false;
    }
}

if ($allFound) {
    echo "Tous les titres d'actualités sont présents dans le rendu HTML.\n";
} else {
    echo "Certains titres d'actualités sont manquants dans le rendu HTML.\n";
}
?>
