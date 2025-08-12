<?php
header('Content-Type: application/json');
require_once '../../../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer et valider les données POST
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$objet = trim($_POST['objet'] ?? '');
$message = trim($_POST['message'] ?? '');
// $telephone = trim($_POST['telephone'] ?? ''); // Not stored in DB currently

if (empty($nom) || empty($email) || empty($objet) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis']);
    exit;
}

// Validation simple de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO contact_visiteurs (nom, email, objet, message) VALUES (:nom, :email, :objet, :message)');
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':objet' => $objet,
        ':message' => $message
    ]);
    echo json_encode(['success' => true, 'message' => 'Votre message a bien été envoyé. Merci de nous avoir contactés.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du message : ' . $e->getMessage()]);
}
?>
