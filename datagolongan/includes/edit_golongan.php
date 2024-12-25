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

    // Ambil data user berdasarkan ID
    $result = mysqli_query($db, "SELECT * FROM tb_golongan WHERE ID_Golongan = '$id'") or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        die("Data dengan ID tersebut tidak ditemukan!");
    }

    $data_golongan = [];
    $query_golongan = mysqli_query($db, "SELECT * FROM tb_golongan") or die(mysqli_error($db));
    while ($row = mysqli_fetch_assoc($query_golongan)) {
        $data_golongan[] = $row;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_golongan = $_POST['id_golongan'];
        $nama_golongan = $_POST['nama_golongan'];
        $tunjangan = str_replace('.', '', $_POST['tunjangan']); 
    
        if (!empty($id_golongan) && !empty($nama_golongan) && !empty($tunjangan)) {
            $query = "UPDATE tb_golongan SET 
                        Nama_Golongan = '$nama_golongan', 
                        Tunjangan = '$tunjangan' 
                        WHERE ID_Golongan = '$id_golongan'";
    
            if (mysqli_query($db, $query)) {
                $_SESSION['update_success'] = true;
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['update_error'] = true;
            }
        } else {
            die("Semua field harus diisi!");
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
    <title>Edit Golongan</title>
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
                    <a href="../" class="nav-link">
                        <i class="bi bi-tags"></i> Data Golongan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datagaji" class="nav-link">
                        <i class="bi bi-cash-stack"></i> Data Gaji
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../datapenggajian" class="nav-link">
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
            <h3 class="mb-4">Edit Data Golongan</h3>

            <!-- Menampilkan alert jika update berhasil -->
            <?php if (isset($_SESSION['update_success'])): ?>
                <div class="alert alert-success" role="alert">
                    Data golongan berhasil diupdate.
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
                    <h5 class="card-title mb-3" style="font-weight:bold;">Edit Data</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_golongan" class="form-label">ID Golongan</label>
                            <select class="form-select" id="id_golongan" name="id_golongan" required>
                                <option value="" disabled selected>Pilih ID Golongan</option>
                                <?php foreach ($data_golongan as $row) { 
                                    $selected = ($row['ID_Golongan'] == $user['ID_Golongan']) ? "selected" : ""; ?>
                                    <option value="<?= $row['ID_Golongan']; ?>" <?= $selected; ?>><?= $row['ID_Golongan']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_golongan" class="form-label">Nama Golongan</label>
                            <select class="form-select" id="nama_golongan" name="nama_golongan" required>
                                <option value="" disabled selected>Pilih Golongan</option>
                                <?php foreach ($data_golongan as $row) { 
                                    $selected = ($row['ID_Golongan'] == $user['ID_Golongan']) ? "selected" : ""; ?>
                                    <option value="<?= $row['Nama_Golongan']; ?>" <?= $selected; ?>><?= $row['Nama_Golongan']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tunjangan" class="form-label">Tunjangan</label>
                            <input type="text" class="form-control" id="tunjangan" name="tunjangan" required oninput="formatRupiah(this)">
                        </div>
                        <button type="submit" class="btn btn-info">Update Data</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let rupiah = split[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }
    </script>
</body>
</html>