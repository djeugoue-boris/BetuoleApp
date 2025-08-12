<?php
session_start();
require_once '../../config.php'; // Connexion PDO à la base

// Vérifier que le formulaire a bien été soumis en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Veuillez remplir le formulaire d'inscription.";
    header('Location: register.php');
    exit;
}

// Récupération et nettoyage
$nom       = trim($_POST['nom'] ?? '');
$prenom    = trim($_POST['prenom'] ?? '');
$sexe      = trim($_POST['sexe'] ?? '');
$naissance = trim($_POST['date_naissance'] ?? '');
$cni       = strtoupper(trim($_POST['cni'] ?? ''));
$email     = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');

$errors = [];

// ✅ Validation
if ($nom === '' || $prenom === '') $errors[] = "Nom et prénom obligatoires.";
if (!in_array($sexe, ['M', 'F'])) $errors[] = "Sexe invalide.";
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $naissance)) $errors[] = "Date de naissance invalide.";
if (!preg_match('/^[A-Z0-9]{6,}$/i', $cni)) $errors[] = "Numéro CNI invalide.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Adresse email invalide.";
// Improved telephone validation: allows +, digits, spaces, 8 to 15 characters
if (!preg_match('/^\+?[0-9\s]{8,15}$/', $telephone)) $errors[] = "Téléphone invalide.";

if ($errors) {
    $_SESSION['error'] = implode(' ', $errors);
    header('Location: register.php');
    exit;
}

try {
    // Vérifier CNI ou email déjà utilisés
    $verif = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? OR cni = ?");
    $verif->execute([$email, $cni]);
    if ($verif->fetch()) {
        $_SESSION['error'] = "Ce CNI ou email est déjà enregistré.";
        header('Location: register.php');
        exit;
    }

    // Générer un matricule unique : BT-yyyy-mm-XXXX-dd
    $annee   = date('Y');
    $mois    = date('m');
    $jour    = date('d');
    $prefixe = "BT-$annee-$mois-";

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE matricule LIKE ?");
    $countStmt->execute(["$prefixe%"]);
    $count = $countStmt->fetchColumn();
    // Correct increment from +10 to +1 for matricule generation
    $increment = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    $matricule = "$prefixe$increment-$jour";

    // Insérer l'apprenant
    $insert = $pdo->prepare("
        INSERT INTO utilisateurs (matricule, nom, prenom, sexe, date_naissance, cni, email, telephone, role, date_inscription)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'apprenant', NOW())
    ");

    $insert->execute([
        $matricule, $nom, $prenom, $sexe, $naissance, $cni, $email, $telephone
    ]);

    $_SESSION['success'] = 'Inscription réussie ! Votre matricule est : '.$matricule.' utilisé pour vous connecter';
    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur serveur : " . $e->getMessage();
    header('Location: register.php');
    exit;
}
