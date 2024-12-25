<?php
include '../../service/database.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data gaji berdasarkan ID
    $result = mysqli_query($db, "SELECT * FROM tb_gaji WHERE ID_Gaji = '$id'") or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        die("Data dengan ID tersebut tidak ditemukan!");
    }

    // Ambil semua data karyawan
    $data_karyawan = mysqli_query($db, "SELECT * FROM tb_karyawan") or die(mysqli_error($db));

    // Ambil semua data gaji (untuk form select ID Gaji)
    $data_gaji = mysqli_query($db, "SELECT ID_Gaji FROM tb_gaji") or die(mysqli_error($db));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_karyawan = $_POST['id_karyawan'];
        $gaji_pokok = str_replace('.', '', $_POST['gaji_pokok']); 
        $bonus = str_replace('.', '', $_POST['bonus']);
        $potongan = str_replace('.', '', $_POST['potongan']); 
        $tanggal_pembayaran = $_POST['tanggal_pembayaran'];

        // Update data ke database
        $query = "UPDATE tb_gaji SET 
                    ID_Karyawan = '$id_karyawan', 
                    Gaji_Pokok = '$gaji_pokok', 
                    Bonus = '$bonus', 
                    Potongan = '$potongan', 
                    Tanggal_Pembayaran = '$tanggal_pembayaran' 
                WHERE ID_Gaji = '$id'";

        if (mysqli_query($db, $query)) {
            $_SESSION['update_success'] = true;
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['update_error'] = true;
        }
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif;">
    <?php include 'header.php'; ?>
    <div class="d-flex" style="padding-top: 70px;">

        <!-- ========== SIDE BAR ========== -->
        <aside class="bg-light border-end vh-100" style="width: 25%; position: fixed;">
            <ul class="nav flex-column p-3">
                <li class="nav-item">
                    <a href="../../dashboard" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datauser" class="nav-link">
                        <i class="bi bi-people"></i> Data User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datakaryawan" class="nav-link">
                        <i class="bi bi-person-workspace"></i> Data Karyawan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datadivisi" class="nav-link">
                        <i class="bi bi-collection"></i> Data Divisi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datagolongan" class="nav-link">
                        <i class="bi bi-tags"></i> Data Golongan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../" class="nav-link">
                        <i class="bi bi-cash-stack"></i> Data Gaji
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../datapenggajian" class="nav-link">
                        <i class="bi bi-cash-coin"></i> Data Penggajian
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../service/logout.php" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i> LogOut
                    </a>
                </li>
            </ul>
        </aside>

        
        <main class="p-4" style="margin-left: 25%; width: 75%;">
            <h3 class="mb-4">Edit Data Gaji</h3>

            <?php if (isset($_SESSION['update_success'])): ?>
                <div class="alert alert-success" role="alert">
                    Data berhasil diperbarui.
                </div>
                <?php unset($_SESSION['update_success']); ?>
            <?php elseif (isset($_SESSION['update_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    Gagal memperbarui data.
                </div>
                <?php unset($_SESSION['update_error']); ?>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_gaji" class="form-label">ID Gaji</label>
                            <input type="text" class="form-control" id="id_gaji" name="id_gaji" value="<?= $user['ID_Gaji'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="id_karyawan" class="form-label">ID Karyawan</label>
                            <select class="form-select" id="id_karyawan" name="id_karyawan" required>
                                <?php
                                while ($row = mysqli_fetch_assoc($data_karyawan)) {
                                    $selected = ($row['ID_Karyawan'] == $user['ID_Karyawan']) ? "selected" : "";
                                    echo "<option value='" . $row['ID_Karyawan'] . "' $selected>" . $row['ID_Karyawan'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                            <input type="text" class="form-control" id="gaji_pokok" name="gaji_pokok" value="<?= number_format($user['Gaji_Pokok'], 0, ',', '.') ?>" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="bonus" class="form-label">Bonus</label>
                            <input type="text" class="form-control" id="bonus" name="bonus" value="<?= number_format($user['Bonus'], 0, ',', '.') ?>" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="potongan" class="form-label">Potongan</label>
                            <input type="text" class="form-control" id="potongan" name="potongan" value="<?= number_format($user['Potongan'], 0, ',', '.') ?>" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" value="<?= $user['Tanggal_Pembayaran'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '');
            if (value) {
                let split = value.split(',');
                let rupiah = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            } else {
                input.value = '';
            }
        }
    </script>
</body>
</html>