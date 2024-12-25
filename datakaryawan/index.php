<?php
include '../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php"); 
    exit;
}

$sukses_message = "";

// Menangani form input untuk menambahkan data karyawan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_karyawan'], $_POST['nama_karyawan'], $_POST['tanggal_lahir'], $_POST['alamat'], $_POST['telepon'], $_POST['id_jabatan'], $_POST['id_divisi'], $_POST['id_golongan'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $id_jabatan = $_POST['id_jabatan'];
    $id_divisi = $_POST['id_divisi'];
    $id_golongan = $_POST['id_golongan'];

    $query = "INSERT INTO tb_karyawan (ID_Karyawan, Nama_Karyawan, Tanggal_Lahir, Alamat, Telepon, ID_Jabatan, ID_Divisi, ID_Golongan) 
                VALUES ('$id_karyawan', '$nama_karyawan', '$tanggal_lahir', '$alamat', '$telepon', '$id_jabatan', '$id_divisi', '$id_golongan')";
    if (mysqli_query($db, $query)) {
        $sukses_message = "Data karyawan berhasil diinput"; 
    } else {
        $error_message = "Gagal menambahkan data";
    }
}

// // Ambil data karyawan untuk ditampilkan di tabel
$data_karyawan = mysqli_query($db, "SELECT * FROM tb_karyawan") or die(mysqli_error($db));

// Ambil data untuk dropdown
$data_jabatan = mysqli_query($db, "SELECT * FROM tb_jabatan") or die(mysqli_error($db));
$data_divisi = mysqli_query($db, "SELECT * FROM tb_divisi") or die(mysqli_error($db));
$data_golongan = mysqli_query($db, "SELECT * FROM tb_golongan") or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
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
        h3 {
            font-family: "Zen Dots", serif;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="d-flex" style="padding-top: 70px;">
        <?php include 'includes/sidebar.php'; ?>
        <main class="p-4" style="margin-left: 25%; width: 75%;">
            <h3 class="mb-4" style="font-family: Zen Dots, serif;">Kelola Data Karyawan</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight:bold;">Tambah Data Karyawan</h5>
                    <form method="POST" action="">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="id_karyawan" class="form-label">ID Karyawan</label>
                                <input type="text" class="form-control" id="id_karyawan" name="id_karyawan" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="id_jabatan" class="form-label">Jabatan</label>
                                <select class="form-select" id="id_jabatan" name="id_jabatan" required>
                                    <option value="" disabled selected>Pilih Jabatan</option>
                                    <?php
                                    if (mysqli_num_rows($data_jabatan) > 0) {
                                        while ($row = mysqli_fetch_assoc($data_jabatan)) {
                                            echo "<option value='" . $row['ID_Jabatan'] . "'>" . $row['Nama_Jabatan'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>Tidak ada data Jabatan</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="id_divisi" class="form-label">Divisi</label>
                                <select class="form-select" id="id_divisi" name="id_divisi" required>
                                    <option value="" disabled selected>Pilih Divisi</option>
                                    <?php while ($row = mysqli_fetch_assoc($data_divisi)) { ?>
                                        <option value="<?= $row['ID_Divisi']; ?>"><?= $row['Nama_Divisi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="id_golongan" class="form-label">Golongan</label>
                                <select class="form-select" id="id_golongan" name="id_golongan" required>
                                    <option value="" disabled selected>Pilih Golongan</option>
                                    <?php while ($row = mysqli_fetch_assoc($data_golongan)) { ?>
                                        <option value="<?= $row['ID_Golongan']; ?>"><?= $row['Nama_Golongan']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- Pesan sukses -->
                        <?php if ($sukses_message): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $sukses_message; ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-info">Tambahkan Data</button>
                    </form>
                </div>
            </div>

            <h3 class="mb-3">Daftar Karyawan</h3>
            <div class="table-responsive">
                <table class="table border-bordered table-striped table-hover">
                    <thead>
                        <tr style="font-size: 15px;">
                            <th scope="col">No</th>
                            <th scope="col">ID Karyawan</th>
                            <th scope="col">Nama Karyawan</th>
                            <th scope="col">Tanggal Lahir</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Telepon</th>
                            <th scope="col">ID Jabatan</th>
                            <th scope="col">ID Divisi</th>
                            <th scope="col">ID Golongan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($data_karyawan)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['ID_Karyawan'] . "</td>";
                            echo "<td>" . $row['Nama_Karyawan'] . "</td>";
                            echo "<td>" . $row['Tanggal_Lahir'] . "</td>";
                            echo "<td>" . $row['Alamat'] . "</td>";
                            echo "<td>" . $row['Telepon'] . "</td>";
                            echo "<td>" . $row['ID_Jabatan'] . "</td>";
                            echo "<td>" . $row['ID_Divisi'] . "</td>";
                            echo "<td>" . $row['ID_Golongan'] . "</td>";
                            echo "<td>
                                    <a href='includes/edit_karyawan.php?id=" . $row['ID_Karyawan'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='#' class='btn btn-danger btn-sm' onclick='confirmDelete(\"" . $row['ID_Karyawan'] . "\")'>Hapus</a>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id_karyawan) {
            if (confirm("Ingin menghapus data?")) {
                window.location.href = "includes/delete_karyawan.php?id=" + id_karyawan;
            }
        }
    </script>
</body>
</html>
