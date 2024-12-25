<?php
include '../../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../../login.php");
    exit;
}

// Cek apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data user berdasarkan ID
    $result = mysqli_query($db, "SELECT * FROM tb_divisi WHERE ID_Divisi = '$id'") or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);

    $data_divisi = mysqli_query($db, "SELECT * FROM tb_divisi") or die(mysqli_error($db));

    // Jika form disubmit, update data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_divisi = $_POST['id_divisi'];
        $nama_divisi = $_POST['nama_divisi'];

        // Update query
        $query = "UPDATE tb_divisi SET 
                Nama_Divisi = '$nama_divisi' 
                WHERE ID_Divisi = '$id_divisi'";
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
                    <a href="../" class="nav-link">
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
            <h3 class="mb-4">Edit Data Divisi</h3>

            <!-- Menampilkan alert jika update berhasil -->
            <?php if (isset($_SESSION['update_success'])): ?>
                <div class="alert alert-success" role="alert">
                    Data divisi berhasil diupdate.
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
                            <label for="id_divisi" class="form-label">ID Divisi</label>
                            <select class="form-select" id="id_divisi" name="id_divisi" required>
                                <option value="" disabled selected>Pilih ID Divisi</option>
                                <?php while ($row = mysqli_fetch_assoc($data_divisi)) { 
                                    $selected = ($row['ID_Divisi'] == $user['ID_Divisi']) ? "selected" : ""; ?>
                                    <option value="<?= $row['ID_Divisi']; ?>" <?= $selected; ?>><?= $row['ID_Divisi']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_divisi" class="form-label">Nama Divisi</label>
                            <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" required>
                        </div>
                        <button type="submit" class="btn btn-info">Update Data</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>