<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../lib/fpdf.php';
include '../../service/database.php';

// Set time zone
date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian');

if (!isset($_GET['id_karyawan'])) {
    die("ID Karyawan tidak ditemukan.");
}

$id_karyawan = mysqli_real_escape_string($db, $_GET['id_karyawan']);

// Query data
$query = "
    SELECT k.Nama_Karyawan, k.ID_Karyawan, gol.Nama_Golongan, k.Alamat, k.Telepon, 
            g.Bonus, g.Gaji_Pokok, g.Potongan, gol.Tunjangan,
            (g.Gaji_Pokok + g.Bonus + gol.Tunjangan - g.Potongan) AS Total_Gaji
    FROM tb_karyawan k
    INNER JOIN tb_gaji g ON k.ID_Karyawan = g.ID_Karyawan
    INNER JOIN tb_golongan gol ON k.ID_Golongan = gol.ID_Golongan
    WHERE k.ID_Karyawan = '$id_karyawan'
    LIMIT 1";

$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data tidak ditemukan untuk ID Karyawan: $id_karyawan.");
}

$gaji_data = mysqli_fetch_assoc($result);

// PDF Design
$pdf = new FPDF('P', 'mm', 'A5');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Header
$pdf->Cell(0, 5, str_repeat('=', 55), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12); // Set font lebih besar untuk header
$pdf->Cell(0, 7, 'PT. Danta Persero', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10); // Kembali ke font normal
$pdf->Cell(0, 5, str_repeat('=', 55), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, '-SlipGaji-', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, str_repeat('=', 55), 0, 1, 'C');
$pdf->Ln(3);

// Informasi Karyawan
$margin_left = 8; // Margin kiri untuk memposisikan detail di tengah
$pdf->SetFont('Arial', '', 9);

$pdf->Cell($margin_left, 5, '', 0, 0); // Margin kiri
$pdf->Cell(50, 5, 'Nama Karyawan', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, $gaji_data['Nama_Karyawan'], 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'ID Karyawan', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, $gaji_data['ID_Karyawan'], 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Golongan', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, $gaji_data['Nama_Golongan'], 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Alamat', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, $gaji_data['Alamat'], 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Telepon', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, $gaji_data['Telepon'], 0, 1, 'L');

// Detail Gaji
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, '-Detail Gaji-', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, str_repeat('=', 55), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Ln(2);

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Gaji Pokok', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, 'Rp ' . number_format($gaji_data['Gaji_Pokok'], 0, ',', '.'), 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Tunjangan', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, 'Rp ' . number_format($gaji_data['Tunjangan'], 0, ',', '.'), 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Bonus', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, 'Rp ' . number_format($gaji_data['Bonus'], 0, ',', '.'), 0, 1, 'L');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Potongan', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, 'Rp ' . number_format($gaji_data['Potongan'], 0, ',', '.'), 0, 1, 'L');

$pdf->Ln(2);
$pdf->Cell(0, 5, str_repeat('-', 70), 0, 1, 'C');

$pdf->Cell($margin_left, 5, '', 0, 0);
$pdf->Cell(50, 5, 'Total Gaji', 0, 0, 'L');
$pdf->Cell(5, 5, ':', 0, 0, 'L');
$pdf->Cell(95, 5, 'Rp ' . number_format($gaji_data['Total_Gaji'], 0, ',', '.'), 0, 1, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, str_repeat('=', 55), 0, 1, 'C');
$pdf->Ln(1);

// Footer
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(0, 5, 'Dicetak: ' . date('l, d-m-Y H:i:s'), 0, 1, 'C');
$pdf->Cell(0, 5, str_repeat('--', 6), 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'Slip_Gaji_' . $gaji_data['ID_Karyawan'] . '.pdf');