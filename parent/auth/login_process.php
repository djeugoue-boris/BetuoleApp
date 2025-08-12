<?php
session_start();
require_once '../../config.php'; // Connexion base

$matricule = trim($_POST['matricule'] ?? '');
$email     = trim($_POST['email'] ?? '');

if ($matricule === '' || $email === '') {
    $_SESSION['error'] = "Veuillez remplir tous les champs.";
    header('Location: login.php');
    exit;
}

try {
    // Vérifier si le matricule et email existent
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE matricule = ? AND email = ?");
    $stmt->execute([$matricule, $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Démarrer la session utilisateur
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['nom']       = $user['nom'];
        $_SESSION['prenom']    = $user['prenom'];
        $_SESSION['matricule'] = $user['matricule'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['success']   = "Bienvenue {$user['prenom']} ! Connexion réussie.";

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: ../apprenant/dashboard_app.php');
        }
        exit;
    } else {
        $_SESSION['error'] = "Matricule ou email incorrect.";
        header('Location: login.php');
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : " . $e->getMessage();
    header('Location: login.php');
    exit;
}

