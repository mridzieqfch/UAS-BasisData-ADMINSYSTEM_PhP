<?php
include '../service/database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

$gaji_data = null;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_gaji = mysqli_real_escape_string($db, $_POST['id_gaji'] ?? '');
    // $id_karyawan = mysqli_real_escape_string($db, $_POST['id_karyawan'] ?? '');

    // if ($id_karyawan) {
    if ($id_gaji) {
        $query = "
            SELECT k.Nama_Karyawan, k.ID_Karyawan, gol.Nama_Golongan, k.Alamat, k.Telepon, 
                    g.Bonus, g.Gaji_Pokok, g.Potongan, gol.Tunjangan,
                    (g.Gaji_Pokok + g.Bonus + gol.Tunjangan - g.Potongan) AS Total_Gaji
            FROM tb_karyawan k
            INNER JOIN tb_gaji g ON k.ID_Karyawan = g.ID_Karyawan
            INNER JOIN tb_golongan gol ON k.ID_Golongan = gol.ID_Golongan

            WHERE g.ID_Gaji = '$id_gaji'
            LIMIT 1";

        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $gaji_data = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Data tidak ditemukan untuk ID Gaji: $id_gaji.";
            // $error_message = "Data tidak ditemukan untuk ID Karyawan: $id_karyawan.";
        }
    } else {
        $error_message = "Silakan masukkan ID Gaji.";
        // $error_message = "Silakan masukkan ID Karyawan.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .slip-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="d-flex" style="padding-top: 70px;">
        <?php include 'includes/sidebar.php'; ?>
        <main class="p-4" style="margin-left: 25%; width: 75%;">
            <h3 class="mb-4">Cetak Slip Gaji</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_karyawan" class="form-label">ID Gaji</label>
                            <input type="text" class="form-control" id="id_gaji" name="id_gaji" required>
                            <!-- <input type="text" class="form-control" id="id_karyawan" name="id_karyawan" required> -->
                        </div>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-info">Tampilkan Slip</button>
                    </form>
                </div>
            </div>

            <?php if ($gaji_data): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="slip-header">Slip Gaji Karyawan</div>
                        <table class="table table-borderless table-striped">
                            <tr>
                                <th>Nama Karyawan</th>
                                <td><?php echo htmlspecialchars($gaji_data['Nama_Karyawan']); ?></td>
                            </tr>
                            <tr>
                                <th>ID Karyawan</th>
                                <td><?php echo htmlspecialchars($gaji_data['ID_Karyawan']); ?></td>
                            </tr>
                            <tr>
                                <th>Golongan</th>
                                <td><?php echo htmlspecialchars($gaji_data['Nama_Golongan']); ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?php echo htmlspecialchars($gaji_data['Alamat']); ?></td>
                            </tr>
                            <tr>
                                <th>No Telp</th>
                                <td><?php echo htmlspecialchars($gaji_data['Telepon']); ?></td>
                            </tr>
                            <tr>
                                <th>Gaji Pokok</th>
                                <td>Rp <?php echo number_format($gaji_data['Gaji_Pokok'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Bonus</th>
                                <td>Rp <?php echo number_format($gaji_data['Bonus'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Tunjangan</th>
                                <td>Rp <?php echo number_format($gaji_data['Tunjangan'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Potongan</th>
                                <td>Rp <?php echo number_format($gaji_data['Potongan'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Total Gaji</th>
                                <td>Rp <?php echo number_format($gaji_data['Total_Gaji'], 0, ',', '.'); ?></td>
                            </tr>
                        </table>
                        <a href="includes/generate_pdf.php?id_karyawan=<?php echo $gaji_data['ID_Karyawan']; ?>" class="btn btn-success mt-3">Cetak PDF</a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>