<?php
require_once '../../../config.php'; // ajuste selon ton projet

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si les champs sont définis
if (
    isset($_POST['id'], $_POST['matricule'], $_POST['nom'], $_POST['prenom'], $_POST['sexe'], 
          $_POST['date_naissance'], $_POST['cni'], $_POST['email'], $_POST['telephone'], $_POST['role'])
) {
    $id = (int) $_POST['id'];
    $matricule = htmlspecialchars(trim($_POST['matricule']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $cni = htmlspecialchars(trim($_POST['cni']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $role = $_POST['role'];

    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $stmt_check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
    $stmt_check->execute([$email, $id]);
    if ($stmt_check->rowCount() > 0) {
        header("Location: index.php?error=email-existe");
        exit;
    }

    // Mise à jour des données
    $stmt = $pdo->prepare("UPDATE utilisateurs SET 
                            matricule = ?, nom = ?, prenom = ?, sexe = ?, date_naissance = ?, 
                            cni = ?, email = ?, telephone = ?, role = ?
                          WHERE id = ?");
    $stmt->execute([
        $matricule, $nom, $prenom, $sexe, $date_naissance,
        $cni, $email, $telephone, $role, $id
    ]);

    header("Location: index.php?success=utilisateur-modifie");
    exit;

} else {
    header("Location: index.php?error=champs-invalides");
    exit;
}
