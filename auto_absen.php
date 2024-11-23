<?php
// Tentukan lokasi file log di public_html
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log'); // Simpan log di 'public_html/php_error.log'

include 'koneksi.php';

// Tanggal hari ini
$tanggal_hari_ini = (new DateTime())->format('Y-m-d');

// Dapatkan hari ini dalam bahasa Indonesia
$hari_ini = date('l');
$hari_mapping = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];
$hari_dalam_bahasa = $hari_mapping[$hari_ini];

// Log informasi jadwal yang sedang diambil
error_log("Mengambil jadwal untuk hari: $hari_dalam_bahasa");

// Ambil semua user yang seharusnya hadir hari ini dan belum ada absen
$jadwal_query = "
    SELECT u.id as user_id, u.nama, u.course, u.hari, u.jam
    FROM users u 
    LEFT JOIN absen a ON u.id = a.user_id AND a.tanggal = '$tanggal_hari_ini'
    WHERE u.hari = '$hari_dalam_bahasa' AND DATE(u.created_at) <= '$tanggal_hari_ini' AND a.user_id IS NULL
    ORDER BY u.jam";
$jadwal_result = $conn->query($jadwal_query);

if (!$jadwal_result) {
    error_log("Gagal menjalankan query: " . $conn->error);
}

// Loop melalui pengguna yang belum absen dan tandai sebagai "Hadir"
while ($row = $jadwal_result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $nama = $row['nama'];
    $course = $row['course'];
    $topik = 'Topik tidak diisi';
    $instruktur = 'Instruktur tidak diisi';
    $status = 'Hadir';

    // Insert ke tabel absen secara otomatis
    $stmt = $conn->prepare("INSERT INTO absen (user_id, nama, pertemuan_ke, tanggal, topik, instruktur, status, course) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Cek apakah statement berhasil dipersiapkan
    if ($stmt) {
        error_log("Menyimpan absen untuk user_id: $user_id");
        $pertemuan_ke = 1; // Sesuaikan logika pertemuan ke sesuai kebutuhan
        $stmt->bind_param("isssssss", $user_id, $nama, $pertemuan_ke, $tanggal_hari_ini, $topik, $instruktur, $status, $course);
        $stmt->execute();
        $stmt->close();
    } else {
        // Log pesan error jika prepare gagal
        error_log("Gagal menyiapkan statement SQL untuk user_id $user_id: " . $conn->error);
    }
}

$conn->close();
