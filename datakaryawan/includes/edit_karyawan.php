<?php
include '../../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data karyawan berdasarkan ID
    $result = mysqli_query($db, "SELECT * FROM tb_karyawan WHERE ID_Karyawan = '$id'") or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        die("Data dengan ID tersebut tidak ditemukan!");
    }

    // Ambil data jabatan, divisi, dan golongan
    $data_jabatan = mysqli_query($db, "SELECT * FROM tb_jabatan") or die(mysqli_error($db));
    $data_divisi = mysqli_query($db, "SELECT * FROM tb_divisi") or die(mysqli_error($db));
    $data_golongan = mysqli_query($db, "SELECT * FROM tb_golongan") or die(mysqli_error($db));

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_karyawan = $_POST['nama_karyawan'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $id_jabatan = $_POST['id_jabatan'];
        $id_divisi = $_POST['id_divisi'];
        $id_golongan = $_POST['id_golongan'];

        // Update query
        $query = "UPDATE tb_karyawan SET 
                    Nama_Karyawan = '$nama_karyawan', 
                    Tanggal_Lahir = '$tanggal_lahir', 
                    Alamat = '$alamat', 
                    Telepon = '$telepon', 
                    ID_Jabatan = '$id_jabatan', 
                    ID_Divisi = '$id_divisi', 
                    ID_Golongan = '$id_golongan' 
                WHERE ID_Karyawan = '$id'";

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
    <title>Edit Karyawan</title>
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
                    <a href="../" class="nav-link">
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
            <h3 class="mb-4">Edit Data Karyawan</h3>

            <!-- Menampilkan alert -->
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
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?= htmlspecialchars($user['Nama_Karyawan']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $user['Tanggal_Lahir'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($user['Alamat']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?= $user['Telepon'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_jabatan" class="form-label">Jabatan</label>
                            <select class="form-select" id="id_jabatan" name="id_jabatan" required>
                                <?php while ($row = mysqli_fetch_assoc($data_jabatan)) { ?>
                                    <option value="<?= $row['ID_Jabatan'] ?>" <?= $row['ID_Jabatan'] == $user['ID_Jabatan'] ? 'selected' : '' ?>>
                                        <?= $row['Nama_Jabatan'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_divisi" class="form-label">Divisi</label>
                            <select class="form-select" id="id_divisi" name="id_divisi" required>
                                <?php while ($row = mysqli_fetch_assoc($data_divisi)) { ?>
                                    <option value="<?= $row['ID_Divisi'] ?>" <?= $row['ID_Divisi'] == $user['ID_Divisi'] ? 'selected' : '' ?>>
                                        <?= $row['Nama_Divisi'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_golongan" class="form-label">Golongan</label>
                            <select class="form-select" id="id_golongan" name="id_golongan" required>
                                <?php while ($row = mysqli_fetch_assoc($data_golongan)) { ?>
                                    <option value="<?= $row['ID_Golongan'] ?>" <?= $row['ID_Golongan'] == $user['ID_Golongan'] ? 'selected' : '' ?>>
                                        <?= $row['Nama_Golongan'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>