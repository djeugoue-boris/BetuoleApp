<?php
// export_archives.php
// Génère et télécharge des archives ZIP pour différents modules

require_once '../../../config.php';

$modules = [
    'ges_formations' => [
        'table' => 'formations',
        'filename' => 'formations.csv',
        'label' => 'Formations'
    ],
    'ges_utilisateurs' => [
        'table' => 'utilisateurs',
        'filename' => 'utilisateurs.csv',
        'label' => 'Utilisateurs'
    ],
    'messages_visiteurs' => [
        'table' => 'contact_visiteurs',
        'filename' => 'messages_visiteurs.csv',
        'label' => 'Messages Visiteurs'
    ],
    'ges_paiements' => [
        'table' => 'paiements',
        'filename' => 'paiements.csv',
        'label' => 'Paiements'
    ],
    'ges_actualites' => [
        'table' => 'actualites',
        'filename' => 'actualites.csv',
        'label' => 'Actualités'
    ],
];

// Si ce n'est pas un export d'archive d'année, vérifier le module
if (!isset($_GET['archive_id'])) {
    if (!isset($_GET['module']) || !isset($modules[$_GET['module']])) {
        http_response_code(400);
        echo 'Module invalide.';
        exit;
    }
}

// Définir les variables module/table/filename uniquement si ce n'est pas un export d'archive d'annee
if (!isset($_GET['archive_id'])) {
    $module = $_GET['module'];
    $table = $modules[$module]['table'];
    $filename = $modules[$module]['filename'];
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sitevitrinedb", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Déterminer le format d'exportation
$format = isset($_GET['format']) && $_GET['format'] === 'pdf' ? 'pdf' : 'zip';

if (!isset($_GET['archive_id'])) {
    if ($format === 'pdf') {
        // Exporter les données en PDF
        require_once '../../fpdf/fpdf.php';
        $query = $pdo->query("SELECT * FROM `$table`");
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) {
            die('Aucune donnée à exporter.');
        }
        $pdf = new FPDF();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Export PDF - ' . $modules[$module]['label'], 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 10);
        // En-têtes
        foreach (array_keys($rows[0]) as $col) {
            $pdf->Cell(40, 8, utf8_decode($col), 1);
        }
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 10);
        // Données
        foreach ($rows as $row) {
            foreach ($row as $val) {
                $pdf->Cell(40, 8, utf8_decode((string)$val), 1);
            }
            $pdf->Ln();
        }
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="archive_' . $module . '_' . date('Ymd_His') . '.pdf"');
        $pdf->Output('D');
        exit;
    }

    // Exporter les données en CSV
    $tmpCsv = tempnam(sys_get_temp_dir(), 'csv');
    $fp = fopen($tmpCsv, 'w');

    $query = $pdo->query("SELECT * FROM `$table`");
    $first = true;
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        if ($first) {
            fputcsv($fp, array_keys($row));
            $first = false;
        }
        fputcsv($fp, $row);
    }
    fclose($fp);

    // Créer l'archive ZIP
    $zip = new ZipArchive();
    $tmpZip = tempnam(sys_get_temp_dir(), 'zip');
    if ($zip->open($tmpZip, ZipArchive::CREATE) === TRUE) {
        $zip->addFile($tmpCsv, $filename);
        $zip->close();
    } else {
        unlink($tmpCsv);
        die('Impossible de créer l\'archive ZIP.');
    }

    // Envoyer le ZIP au navigateur
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="archive_' . $module . '_' . date('Ymd_His') . '.zip"');
    header('Content-Length: ' . filesize($tmpZip));
    readfile($tmpZip);

    // Nettoyage
    unlink($tmpCsv);
    unlink($tmpZip);
    exit;
}

// Gestion du téléchargement d'une archive d'année antérieure
if (isset($_GET['archive_id'])) {
    $archive_id = intval($_GET['archive_id']);
    $stmt = $pdo->prepare("SELECT annee, donnees FROM archives WHERE id = ?");
    $stmt->execute([$archive_id]);
    $archive = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$archive) {
        die('Archive introuvable.');
    }
    $donnees = json_decode($archive['donnees'], true);
    if (!$donnees) {
        die('Données d\'archive invalides.');
    }
    $format = isset($_GET['format']) && $_GET['format'] === 'pdf' ? 'pdf' : (isset($_GET['format']) && $_GET['format'] === 'sql' ? 'sql' : 'zip');
    $annee = $archive['annee'];
    if ($format === 'pdf') {
        require_once '../../fpdf/fpdf.php';
        $pdf = new FPDF();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Export PDF - Archive année ' . $annee, 0, 1, 'C');
        foreach ($donnees as $table => $rows) {
            if (empty($rows)) continue;
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, strtoupper($table), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 9);
            foreach (array_keys($rows[0]) as $col) {
                $pdf->Cell(40, 7, utf8_decode($col), 1);
            }
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 9);
            foreach ($rows as $row) {
                foreach ($row as $val) {
                    $pdf->Cell(40, 7, utf8_decode((string)$val), 1);
                }
                $pdf->Ln();
            }
            $pdf->Ln(3);
        }
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="archive_annee_' . $annee . '_' . date('Ymd_His') . '.pdf"');
        $pdf->Output('D');
        exit;
    } elseif ($format === 'sql') {
        $sql = "-- Export SQL de l'archive année $annee\n";
        foreach ($donnees as $table => $rows) {
            if (empty($rows)) continue;
            $cols = array_keys($rows[0]);
            $sql .= "\n-- Table `$table`\n";
            foreach ($rows as $row) {
                $values = array_map(function($v) use ($pdo) {
                    if ($v === null) return 'NULL';
                    return "'" . str_replace("'", "''", $v) . "'";
                }, array_values($row));
                $sql .= "INSERT INTO `$table` (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', $values) . ");\n";
            }
        }
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="archive_annee_' . $annee . '_' . date('Ymd_His') . '.sql"');
        echo $sql;
        exit;
    } else {
        // Générer un ZIP avec un CSV par table
        $tmpZip = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE) === TRUE) {
            foreach ($donnees as $table => $rows) {
                if (empty($rows)) continue;
                $tmpCsv = tempnam(sys_get_temp_dir(), 'csv');
                $fp = fopen($tmpCsv, 'w');
                fputcsv($fp, array_keys($rows[0]));
                foreach ($rows as $row) {
                    fputcsv($fp, $row);
                }
                fclose($fp);
                $zip->addFile($tmpCsv, $table . '.csv');
            }
            $zip->close();
        } else {
            die('Impossible de créer l\'archive ZIP.');
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="archive_annee_' . $annee . '_' . date('Ymd_His') . '.zip"');
        header('Content-Length: ' . filesize($tmpZip));
        readfile($tmpZip);
        // Nettoyage
        foreach ($donnees as $table => $rows) {
            if (empty($rows)) continue;
            @unlink($tmpCsv);
        }
        @unlink($tmpZip);
        exit;
    }
}
