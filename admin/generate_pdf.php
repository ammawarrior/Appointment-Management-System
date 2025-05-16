<?php
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';
session_start();

// Include Database Connection
include 'temp_db.php';

// Get user role from session
$user_role = $_SESSION['role'];

// Define lab_id mapping
$labNames = [
    1 => 'Metrology Calibration',
    2 => 'Chemical Analysis',
    3 => 'Microbiological Analysis',
    4 => 'Shelf-life Analysis'
];

// Set query based on role
if ($user_role == 1) { // Admin sees all
    $query = "SELECT unique_id, lab_id, category, full_name, contact_number, submission_date_selected, quantity, status FROM submissions";
} else {
    $lab_id = $user_role - 1; // Map role to lab_id
    $query = "SELECT unique_id, lab_id, category, full_name, contact_number, submission_date_selected, quantity, status FROM submissions WHERE lab_id = $lab_id";
}
$result = $conn->query($query);

// Create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Reservations Report');
$pdf->SetHeaderData('', 0, 'Reservations Report', date('Y-m-d'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 10);
$pdf->AddPage();

// Table Header
$html = '
<h2>Reservations Report</h2>
<table border="1" cellpadding="5">
    <thead>
        <tr style="font-weight: bold; background-color: #ddd;">
            <th>Transaction No.</th>
            <th>Sample Classification</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Client Name</th>
            <th>Phone Number</th>
            <th>Date Reserved</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';

// Add rows dynamically
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labName = isset($labNames[$row['lab_id']]) ? $labNames[$row['lab_id']] : 'Unknown';
        $statusText = ($row['status'] == 1) ? "Pending" : (($row['status'] == 2) ? "Confirmed" : "Rejected");

        $html .= '<tr>
            <td>' . $row['unique_id'] . '</td>
            <td>' . $labName . '</td>
            <td>' . $row['category'] . '</td>
            <td>' . $row['quantity'] . '</td>
            <td>' . $row['full_name'] . '</td>
            <td>' . $row['contact_number'] . '</td>
            <td>' . $row['submission_date_selected'] . '</td>
            <td>' . $statusText . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="8" align="center">No data available</td></tr>';
}

$html .= '</tbody></table>';

// Output the HTML content into PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output the PDF file
$pdf->Output('Reservations_Report.pdf', 'D'); // "D" forces download

$conn->close();
?>
