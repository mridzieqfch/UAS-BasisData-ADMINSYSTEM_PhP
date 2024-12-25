<?php
include '../service/database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

$sukses_message = "";

// Mengambil data untuk tabel
$query_data = "
    SELECT k.Nama_Karyawan, k.ID_Karyawan, gol.Nama_Golongan, k.Alamat, k.Telepon, 
           g.ID_Gaji, g.Bonus, g.Gaji_Pokok, g.Potongan, gol.Tunjangan, 
           (g.Gaji_Pokok + g.Bonus + gol.Tunjangan - g.Potongan) AS Total_Gaji, g.Tanggal_Pembayaran
    FROM tb_karyawan k
    INNER JOIN tb_gaji g ON k.ID_Karyawan = g.ID_Karyawan
    INNER JOIN tb_golongan gol ON k.ID_Golongan = gol.ID_Golongan
";

$result_data = mysqli_query($db, $query_data) or die("Error pada query: " . mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="d-flex" style="padding-top: 70px;">
        <?php include 'includes/sidebar.php'; ?>
        <main class="p-4" style="margin-left: 25%; width: 75%;">
            <h3 class="mb-3">Daftar Penggajian</h3>
            <?php
            echo "<td>
                    <a href='../datacetakgaji?id=" . "' class='btn btn-success btn-sm'>Cetak Slip Gaji</a>
                </td>";
            ?>
            <div class="table-responsive">
                <table class="table border-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 14px;">
                            <th scope="col">No</th>
                            <th scope="col">ID Gaji</th>
                            <th scope="col">ID Karyawan</th>
                            <th scope="col">Nama Karyawan</th>
                            <th scope="col">Gaji Pokok</th>
                            <th scope="col">Bonus</th>
                            <th scope="col">Potongan</th>
                            <th scope="col">Tunjangan</th>
                            <th scope="col">Total Gaji</th>
                            <th scope="col">Tanggal Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result_data)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['ID_Gaji']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ID_Karyawan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Nama_Karyawan']) . "</td>";
                            echo "<td>Rp " . number_format($row['Gaji_Pokok'], 0, ',', '.') . "</td>";
                            echo "<td>Rp " . number_format($row['Bonus'], 0, ',', '.') . "</td>";
                            echo "<td>Rp " . number_format($row['Potongan'], 0, ',', '.') . "</td>";
                            echo "<td>Rp " . number_format($row['Tunjangan'], 0, ',', '.') . "</td>";
                            echo "<td>Rp " . number_format($row['Total_Gaji'], 0, ',', '.') . "</td>";
                            echo "<td>" . htmlspecialchars($row['Tanggal_Pembayaran']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
