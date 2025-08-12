<?php
require_once '../../../config.php'; // adapte le chemin selon ton arborescence

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et validation des données
    $id = (int) $_POST['id'];
    $matricule = trim($_POST['matricule']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $cni = trim($_POST['cni']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $role = $_POST['role'];

    // Vérification des champs obligatoires
    if ($id && $matricule && $nom && $prenom && $sexe && $date_naissance && $cni && $email && $telephone && $role) {
        try {
            $stmt = $pdo->prepare("UPDATE utilisateurs 
                SET matricule = ?, nom = ?, prenom = ?, sexe = ?, date_naissance = ?, cni = ?, email = ?, telephone = ?, role = ? 
                WHERE id = ?");
            $stmt->execute([
                $matricule, $nom, $prenom, $sexe, $date_naissance, $cni, $email, $telephone, $role, $id
            ]);

            header("Location: apprenants.php?success=modif-apprenant");
            exit;
        } catch (PDOException $e) {
            header("Location: apprenants.php?error=modif-echouee");
            exit;
        }
    } else {
        header("Location: apprenants.php?error=champs-invalides");
        exit;
    }
} else {
    header("Location: apprenants.php");
    exit;
}
