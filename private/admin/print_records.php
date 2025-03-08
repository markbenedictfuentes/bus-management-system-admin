<?php
// I-include ang TCPDF library
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');

// Sample safety records (Dapat ito ay manggaling sa database sa actual na sistema)
// Napalitan na ang keys: 'bus_id' → 'bus_number' at nagdagdag ng 'plate_number'
$safety_records = [
    [
        'id' => 1,
        'bus_number' => 'Bus-101',  // Bus Name
        'plate_number' => 'ABC-1234', // Bus Plate
        'inspection_date' => '2025-03-05',
        'safety_score' => 85,
        'comments' => 'Good',
        'bus_proof' => 'proof1.jpg',
        'passenger_proof' => ''
    ],
    [
        'id' => 2,
        'bus_number' => 'Bus-102',
        'plate_number' => 'XYZ-5678',
        'inspection_date' => '2025-03-05',
        'safety_score' => 75,
        'comments' => 'Needs Improvement',
        'bus_proof' => '',
        'passenger_proof' => 'proof2.jpg'
    ]
];

// Hiwalayin ang records base sa safety_score
$compliant = [];
$non_compliant = [];
foreach ($safety_records as $record) {
    if ($record['safety_score'] >= 80) {
        $compliant[] = $record;
    } else {
        $non_compliant[] = $record;
    }
}

// Gumawa ng bagong PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('Safety Compliance System');
$pdf->SetAuthor('Safety Inspector');
$pdf->SetTitle('Safety Inspection Report');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Function para gumawa ng table
function generateTable($title, $records, $pdf) {
    $pdf->AddPage();
    $html = '<h2 style="text-align:center; color:#007BFF;">' . $title . '</h2>';
    // Binago ang table header para isama ang Bus Name at Bus Plate
    $html .= '<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">';
    $html .= '<thead style="background-color:#f2f2f2;">
                <tr>
                    <th>ID</th>
                    <th>Bus Name</th>
                    <th>Bus Plate</th>
                    <th>Inspection Date</th>
                    <th>Safety Score</th>
                    <th>Comments</th>
                </tr>
              </thead>
              <tbody>';
    
    if (!empty($records)) {
        foreach ($records as $record) {
            $html .= '<tr>
                        <td>' . $record['id'] . '</td>
                        <td>' . htmlspecialchars($record['bus_number']) . '</td>
                        <td>' . htmlspecialchars($record['plate_number']) . '</td>
                        <td>' . date("F d, Y", strtotime($record['inspection_date'])) . '</td>
                        <td style="text-align:center; font-weight:bold; color:' . ($record['safety_score'] >= 80 ? 'green' : 'red') . ';">' . $record['safety_score'] . '</td>
                        <td>' . htmlspecialchars($record['comments']) . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="6" style="text-align:center; font-style:italic;">No records found.</td></tr>';
    }
    $html .= '</tbody></table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
}

generateTable('Compliant Safety Records', $compliant, $pdf);
generateTable('Non-Compliant Safety Records', $non_compliant, $pdf);
date_default_timezone_set('Asia/Manila');
$filename = 'safety_records_' . date('Ymd_His') . '.pdf';
$pdf->Output($filename, 'I');
?>
