<?php
include '../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

$sukses_message = "";
$eror_message = "";

// Menangani form input untuk menambahkan data divisi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_divisi'], $_POST['nama_divisi'])) {
    $id_divisi = $_POST['id_divisi'];
    $nama_divisi = $_POST['nama_divisi'];

    $check_query = "SELECT * FROM tb_divisi WHERE ID_Divisi = '$id_divisi'";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $eror_message = "ID Divisi '$id_divisi' sudah ada. Silakan gunakan ID Divisi yang berbeda.";
    } else {
        // Lanjutkan dengan query insert
        $query = "INSERT INTO tb_divisi (ID_Divisi, Nama_Divisi) VALUES ('$id_divisi', '$nama_divisi')";

        if (mysqli_query($db, $query)) {
            $sukses_message = "Data divisi berhasil diinput"; 
        } else {
            $error_message = "Gagal menambahkan data"; 
        }
    }
}

// Ambil data divisi untuk ditampilkan di tabel
$data_divisi = mysqli_query($db, "SELECT * FROM tb_divisi") or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Divisi</title>
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
            <h3 class="mb-4">Kelola Data Divisi</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="font-weight:bold;">Tambah Data Divisi</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_divisi" class="form-label">ID Divisi</label>
                            <input type="text" class="form-control" id="id_divisi" name="id_divisi" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_divisi" class="form-label">Nama Divisi</label>
                            <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" required>
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

            <h3 class="mb-3">Daftar Divisi</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">ID Divisi</th>
                        <th scope="col">Nama Divisi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data_divisi)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['ID_Divisi'] . "</td>";
                        echo "<td>" . $row['Nama_Divisi'] . "</td>";
                        echo "<td>
                                <a href='includes/edit_divisi.php?id=" . $row['ID_Divisi'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='#' class='btn btn-danger btn-sm' onclick='confirmDelete(\"" . $row['ID_Divisi'] . "\")'>Hapus</a>
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
        function confirmDelete(id_divisi) {
            if (confirm("Ingin menghapus data?")) {
                window.location.href = "includes/delete_divisi.php?id=" + id_divisi;
            }
        }
    </script>
</body>
</html>
