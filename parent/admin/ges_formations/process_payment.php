<?php
session_start();                      
require_once '../../../config.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les données POST
$idF   = (int)$_POST['id_formation'];
$mode  = $_POST['mode'];
$mont  = floatval($_POST['montant']);
$tel   = trim($_POST['tel']);

// Ici, tu fais l’appel à Cinpay (pseudo-code) :
/*
$response = Cinpay::pay([
  'amount'       => $mont,
  'currency'     => 'XAF',
  'msisdn'       => $tel,
  'provider'     => $mode,       // MTN ou Orange
  'description'  => "Inscription Formation #{$idF}"
]);
*/

$success = true;  // simuler le succès
if ($success) {
    // Enregistre la transaction
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("INSERT INTO paiements (id_utilisateur, montant, mode_paiement, statut) VALUES (?, ?, ?, ?)");
    // remplace 1 par l'id de l'apprenant connecté
    $stmt->execute([1, $mont, $mode, 'en attente']);

    header("Location: detail.php?id={$idF}&success=paiement");
    exit;
} else {
    header("Location: detail.php?id={$idF}&error=paiement");
    exit;
}
