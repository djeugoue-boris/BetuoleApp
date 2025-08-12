<?php
require_once '../../../config.php'; // adapte le chemin selon ton arborescence

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si toutes les données sont présentes
if (
    isset($_POST['matricule'], $_POST['nom'], $_POST['prenom'], $_POST['sexe'], $_POST['date_naissance'],
          $_POST['cni'], $_POST['email'], $_POST['telephone'], $_POST['role'])
) {
    $matricule = htmlspecialchars(trim($_POST['matricule']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $cni = htmlspecialchars(trim($_POST['cni']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $role = $_POST['role'];

    // Vérifier si l'email existe déjà (optionnel)
    $stmt_check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt_check->execute([$email]);
    if ($stmt_check->rowCount() > 0) {
        header("Location: index.php?error=email-existe");
        exit;
    }

    // Insertion dans la base
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (matricule, nom, prenom, sexe, date_naissance, cni, email, telephone, role)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $matricule, $nom, $prenom, $sexe, $date_naissance, $cni, $email, $telephone, $role
    ]);

    header("Location: index.php?success=utilisateur-ajoute");
    exit;

} else {
    header("Location: index.php?error=champs-invalides");
    exit;
}
