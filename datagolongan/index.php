<?php
include '../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

$sukses_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_golongan'], $_POST['nama_golongan'], $_POST['tunjangan'])) {
    $id_golongan = mysqli_real_escape_string($db, $_POST['id_golongan']);
    $nama_golongan = mysqli_real_escape_string($db, $_POST['nama_golongan']);
    $tunjangan = str_replace('.', '', $_POST['tunjangan']); 

    $check_query = "SELECT * FROM tb_golongan WHERE ID_Golongan = '$id_golongan'";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $eror_message = "ID Golongan '$id_golongan' sudah ada. Silakan gunakan ID Golongan yang berbeda.";
    } else {
        // Lanjutkan dengan query insert
        $query = "INSERT INTO tb_golongan (ID_Golongan, Nama_Golongan, Tunjangan) 
                    VALUES ('$id_golongan', '$nama_golongan', '$tunjangan')";

        if (mysqli_query($db, $query)) {
            $sukses_message = "Data golongan berhasil diinput";
        } else {
            $error_message = "Error: " . mysqli_error($db);
        }
    }
}

$data_divisi = mysqli_query($db, "SELECT * FROM tb_golongan") or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Golongan</title>
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
            <h3 class="mb-4">Kelola Data Golongan</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="font-weight:bold;">Tambah Data Golongan</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_golongan" class="form-label">ID Golongan</label>
                            <input type="text" class="form-control" id="id_golongan" name="id_golongan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_divisi" class="form-label">Nama Golongan</label>
                            <input type="text" class="form-control" id="nama_golongan" name="nama_golongan" required>
                        </div>
                        <div class="mb-3">
                            <label for="tunjangan" class="form-label">Tunjangan</label>
                            <input type="text" class="form-control" id="tunjangan" name="tunjangan" required oninput="formatRupiah(this)">
                        </div>
                        <?php if ($sukses_message): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $sukses_message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($eror_message): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($eror_message); ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-info">Tambahkan Data</button>
                    </form>
                </div>
            </div>

            <h3 class="mb-3">Daftar Golongan</h3>
            <table class="table table-striped">
            <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">ID Golongan</th>
                        <th scope="col">Nama Golongan</th>
                        <th scope="col">Tunjangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data_divisi)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['ID_Golongan'] . "</td>";
                        echo "<td>" . $row['Nama_Golongan'] . "</td>";
                        echo "<td>Rp " . number_format($row['Tunjangan'], 0, ',', '.') . "</td>"; 
                        echo "<td>
                                <a href='includes/edit_golongan.php?id=" . $row['ID_Golongan'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='#' class='btn btn-danger btn-sm' onclick='confirmDelete(\"" . $row['ID_Golongan'] . "\")'>Hapus</a>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
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
        function confirmDelete(id_karyawan) {
            if (confirm("Ingin menghapus data?")) {
                window.location.href = "includes/delete_golongan.php?id=" + id_karyawan;
            }
        }
    </script>
</body>
</html>
