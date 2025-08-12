<?php
// test_alerts.php
// Test des alertes SweetAlert2 liées aux actualités dans index.php selon les paramètres GET

function testAlert($param, $value, $expectedText) {
    $_GET = [$param => $value];

    ob_start();
    include '../index.php';
    $html = ob_get_clean();

    if (strpos($html, $expectedText) !== false) {
        echo "Test alerte pour $_GET[$param]=$value : OK\n";
    } else {
        echo "Test alerte pour $_GET[$param]=$value : ÉCHEC\n";
    }
}

// Tests alertes modification
testAlert('update', '1', 'Modification réussie');
testAlert('update', '0', 'Impossible de modifier l’actualité');

// Tests alertes suppression
testAlert('success', 'suppression', 'L\'actualité a été supprimée avec succès.');

// Tests alertes ajout
testAlert('success', '1', 'Actualité ajoutée avec succès');

// Tests alertes erreur
testAlert('error', '1', 'Une erreur est survenue');

?>
