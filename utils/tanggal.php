<?php
function formatTanggalIndo($createdAt)
{
    // Array bulan dan hari dalam bahasa Indonesia
    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $hari = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];

    // Membuat objek DateTime dari string tanggal
    $date = new DateTime($createdAt);

    // Mendapatkan nama hari dan bulan dalam Bahasa Indonesia
    $namaHari = $hari[$date->format('l')];  // format('l') untuk nama hari
    $namaBulan = $bulan[$date->format('F')]; // format('F') untuk nama bulan

    // Format tanggal dalam Bahasa Indonesia
    return $namaHari . ', ' . $date->format('d') . ' ' . $namaBulan . ' ' . $date->format('Y');
}
