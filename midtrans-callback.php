<?php
// Load Midtrans SDK

use Dotenv\Dotenv;

require_once dirname(__FILE__) . '/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

\Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

include 'koneksi.php';

// Get the JSON payload
$json_str = file_get_contents('php://input');
$notification = json_decode($json_str, true);

if (!$notification) {
    error_log("Invalid input in Midtrans Callback");
    die("Invalid input");
}

// Extract necessary fields from the notification
$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'] ?? null;
$payment_type = $notification['payment_type'] ?? null;
$va_number = $notification['va_numbers'][0]['va_number'] ?? null;
$bank = $notification['va_numbers'][0]['bank'] ?? null;
$store = $notification['store'] ?? null;
$settlement_time = $notification['settlement_time'] ?? null;

// Check transaction success status
if ($transaction_status == 'settlement' || ($transaction_status == 'capture' && $fraud_status == 'accept')) {
    // Update the `pendaftaran` table with status, payment_type, va_number, bank, store, and settlement_time
    $sql_update_pendaftaran = "UPDATE pendaftaran SET 
        status='settled', 
        payment_type='$payment_type', 
        va_number='$va_number', 
        bank='$bank',
        store='$store',
        settlement_time='$settlement_time'
        WHERE order_id='$order_id'";

    if ($conn->query($sql_update_pendaftaran) === TRUE) {
        echo "Status pendaftaran diperbarui menjadi settled, payment_type, va_number, bank, store, dan settlement_time disimpan.";
    } else {
        error_log("Database update error: " . $conn->error);
        echo "Database error: " . $conn->error;
    }
} else {
    echo "Transaksi belum berhasil atau tidak valid.";
}

$conn->close();
