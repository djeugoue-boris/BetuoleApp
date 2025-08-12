cette<?php
require_once '../../../config.php';

// Load dompdf
require_once '../../../vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_GET['id'])) {
    die("ID de paiement manquant.");
}

$id = (int) $_GET['id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch payment and user info
    $stmt = $pdo->prepare("SELECT p.*, u.nom, u.prenom, u.email, u.filiere FROM paiements p JOIN utilisateurs u ON p.id_utilisateur = u.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        die("Paiement non trouvé.");
    }

    // Prepare logo image as base64
    $logoPath = realpath('../../../img/logo.jpg');
    $logoData = '';
    if ($logoPath && file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    // Prepare HTML content for PDF
    $html = '
    <div style="text-align: center; font-family: Arial, sans-serif; margin-bottom: 20px;">
      <div style="float: left; width: 33%; text-align: left;">
        <h3>République du Cameroun</h3>
        <p>Paix - Travail - Patrie</p>
      </div>
      <div style="float: right; width: 33%; text-align: right;">
        <h3>Republic of Cameroon</h3>
        <p>Peace - Work - Fatherland</p>
      </div>
      <div style="clear: both;"></div>
    </div>


    <div style="position: relative; font-family: Arial, sans-serif;">

      <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: 0;">
        <img src="' . $logoData . '" alt="Logo Betuole" style="max-width: 80%; max-height: 80vh;">
      </div>

      <div style="position: relative; z-index: 1;">
        <h2 style="text-align: center; margin-bottom: 30px;">Reçu de paiement / Payment Receipt</h2>

        <table style="width: 100%; font-family: Arial, sans-serif; margin-bottom: 20px;">
          <tr>
            <td><strong>Nom / Name:</strong> ' . htmlspecialchars($payment['nom'] . ' ' . $payment['prenom']) . '</td>
            <td><strong>Email:</strong> ' . htmlspecialchars($payment['email']) . '</td>
          </tr>
          <tr>
            <td><strong>Filière / Field:</strong> ' . htmlspecialchars($payment['filiere']) . '</td>
            <td><strong>Type de paiement / Payment Type:</strong> ' . htmlspecialchars($payment['type_paiement']) . '</td>
          </tr>
          ' . (
            in_array($payment['mode_paiement'], ['MTN', 'MOMO']) && !empty($payment['reference']) ?
            '<tr><td colspan="2" style="padding: 10px 0; text-align:center;"><span style="display:inline-block; background:#003366; color:#fff; font-size:1.2em; font-weight:bold; letter-spacing:2px; border-radius:8px; padding:8px 24px; border:2px dashed #50c0e9;">Référence transaction : ' . htmlspecialchars($payment['reference']) . '</span><br><span style="color:#117a8b; font-size:1em; font-weight:600;">Ce paiement peut s\'effectuer en ligne en utilisant cette référence lors de la transaction.</span></td></tr>'
            : ''
          ) . '
          <tr>
            <td><strong>Montant / Amount:</strong> ' . number_format($payment['montant'], 2, ',', ' ') . ' FCFA</td>
            <td><strong>Mode de paiement / Payment Mode:</strong> ' . htmlspecialchars($payment['mode_paiement']) . '</td>
          </tr>
          <tr>
            <td><strong>Statut / Status:</strong> ' . htmlspecialchars($payment['statut']) . '</td>
            <td><strong>Date de paiement / Payment Date:</strong> ' . date('d/m/Y H:i', strtotime($payment['date_paiement'])) . '</td>
          </tr>
        </table>

    <div style="margin-top: 40px; font-family: Arial, sans-serif;">
      <p>Merci pour votre paiement. / Thank you for your payment.</p>
    </div>

    <div style="margin-top: 60px; font-family: Arial, sans-serif;">
      <table style="width: 100%; border-top: 1px solid #000; padding-top: 20px;">
        <tr>
          <td style="width: 50%; text-align: center;">
            <p>Signature</p>
            <br><br>
            <p>_________________________</p>
          </td>
          <td style="width: 50%; text-align: center;">
            <p>Visa</p>
            <br><br>
            <p>_________________________</p>
          </td>
        </tr>
      </table>
      <p style="text-align: right; margin-top: 40px;">Date du: ..................</p>
    </div>

    <footer style="position: fixed; bottom: 10px; width: 100%; text-align: center; font-family: Arial, sans-serif; font-size: 12px; color: #666;">
      by.betuole app v1.1.0.1
    </footer>
    ';

    // Instantiate dompdf and load HTML
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // (Optional) Setup paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to browser for download
    $filename = 'recu_paiement_' . $payment['id'] . '.pdf';
    $dompdf->stream($filename, ['Attachment' => true]);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
