<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Fetch user details from the database
$user_query = "SELECT id, nama, email, course, hari, jam, order_id, created_at FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
if ($user_result->num_rows == 0) {
    echo "User not found.";
    exit;
}
$user_data = $user_result->fetch_assoc();

// Fetch payment details from the `pendaftaran` table based on `order_id`
$order_id = $user_data['order_id'];
if ($order_id) {
    $payment_query = "SELECT order_id, status, payment_type, store, va_number, bank FROM pendaftaran WHERE order_id = '$order_id'";
    $payment_result = $conn->query($payment_query);
    $payment_data = ($payment_result->num_rows > 0) ? $payment_result->fetch_assoc() : [];
} else {
    $payment_data = [];
}

$absen_query = "SELECT id, pertemuan_ke, tanggal, topik, instruktur, status, absen_date FROM absen WHERE user_id = $user_id";
$absen_result = $conn->query($absen_query);
$conn->close();

// Create a PDF file with the user's details
$user_name = $user_data['nama'];
$timestamp = time();
$pdf_filename = $user_name . '_' . $timestamp . '.pdf';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Detail Pengguna');
$pdf->SetSubject('Detail Pengguna dan Absen');
$pdf->SetKeywords('PDF, pengguna, absen, detail');

// $pdf->SetHeaderData('', 0, 'Detail Pengguna', 'Admin');

$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

$html = '<h1>Detail Pengguna</h1>';
$html .= '<p><strong>Nama:</strong> ' . htmlspecialchars($user_data['nama']) . '</p>';
$html .= '<p><strong>Email:</strong> ' . htmlspecialchars($user_data['email']) . '</p>';
$html .= '<p><strong>Kursus:</strong> ' . htmlspecialchars($user_data['course']) . '</p>';
$html .= '<p><strong>Hari:</strong> ' . htmlspecialchars($user_data['hari']) . '</p>';
$html .= '<p><strong>Jam:</strong> ' . htmlspecialchars($user_data['jam']) . '</p>';
$html .= '<p><strong>Tanggal Pendaftaran:</strong> ' . htmlspecialchars($user_data['created_at']) . '</p>';

$html .= '<h2>Detail Pembayaran</h2>';
if ($payment_data) {
    $html .= '<p><strong>Order ID:</strong> ' . htmlspecialchars($payment_data['order_id']) . '</p>';
    $html .= '<p><strong>Status:</strong> ' . htmlspecialchars($payment_data['status']) . '</p>';
    $html .= '<p><strong>Payment Type:</strong> ' . htmlspecialchars($payment_data['payment_type']) . '</p>';
    if ($payment_data['payment_type'] == 'cstore') {
        $html .= '<p><strong>Store:</strong> ' . htmlspecialchars($payment_data['store']) . '</p>';
    } elseif ($payment_data['payment_type'] == 'bank_transfer') {
        $html .= '<p><strong>Bank:</strong> ' . htmlspecialchars($payment_data['bank']) . '</p>';
        $html .= '<p><strong>VA Number:</strong> ' . htmlspecialchars($payment_data['va_number']) . '</p>';
    }
} else {
    $html .= '<p>Detail pembayaran tidak tersedia.</p>';
}

$html .= '<h2>Data Absen</h2>';
$html .= '<table border="1" cellpadding="4">';
$html .= '<tr><th>Pertemuan Ke</th><th>Tanggal</th><th>Topik</th><th>Instruktur</th><th>Status</th><th>Tanggal Absen</th></tr>';
if ($absen_result->num_rows > 0) {
    while ($absen = $absen_result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($absen['pertemuan_ke']) . '</td>';
        $html .= '<td>' . htmlspecialchars($absen['tanggal']) . '</td>';
        $html .= '<td>' . htmlspecialchars($absen['topik']) . '</td>';
        $html .= '<td>' . htmlspecialchars($absen['instruktur']) . '</td>';
        $html .= '<td>' . htmlspecialchars($absen['status']) . '</td>';
        $html .= '<td>' . htmlspecialchars($absen['absen_date']) . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="6">Tidak ada data absen untuk pengguna ini.</td></tr>';
}
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output($pdf_filename, 'I');
