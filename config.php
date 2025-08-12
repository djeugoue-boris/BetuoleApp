<?php
// Informations de connexion
$host = 'localhost';
$dbname = 'sitevitrinedb';
$username = 'root';       // à adapter si tu utilises un autre utilisateur
$password = '';           // à adapter selon ton mot de passe MySQL

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuration des erreurs PDO en mode Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Facultatif : Forcer les requêtes préparées natives
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
