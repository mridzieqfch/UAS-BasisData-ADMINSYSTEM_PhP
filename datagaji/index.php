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
$eror_message = "";

// Proses penambahan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_gaji = mysqli_real_escape_string($db, $_POST['id_gaji'] ?? '');
    $id_karyawan = mysqli_real_escape_string($db, $_POST['id_karyawan'] ?? '');
    $gaji_pokok = str_replace(['.', ','], '', $_POST['gaji_pokok'] ?? '');
    $bonus = str_replace(['.', ','], '', $_POST['bonus'] ?? '');
    $potongan = str_replace(['.', ','], '', $_POST['potongan'] ?? '');
    $tanggal_pembayaran = mysqli_real_escape_string($db, $_POST['tanggal_pembayaran'] ?? '');

    if ($id_gaji && $id_karyawan && $gaji_pokok && $bonus && $potongan && $tanggal_pembayaran) {
        $query = "INSERT INTO tb_gaji (ID_Gaji, ID_Karyawan, Gaji_Pokok, Bonus, Potongan, Tanggal_Pembayaran) 
            VALUES ('$id_gaji', '$id_karyawan', '$gaji_pokok', '$bonus', '$potongan', '$tanggal_pembayaran')";

        // Periksa apakah ID_Gaji sudah ada
        $check_query = "SELECT * FROM tb_gaji WHERE ID_Gaji = '$id_gaji'";
        $check_result = mysqli_query($db, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $eror_message = "ID Gaji '$id_gaji' sudah ada. Silakan gunakan ID Gaji yang berbeda.";
        } else {
            // Lanjutkan dengan query insert
            $query = "INSERT INTO tb_gaji (ID_Gaji, ID_Karyawan, Gaji_Pokok, Bonus, Potongan, Tanggal_Pembayaran) 
                VALUES ('$id_gaji', '$id_karyawan', '$gaji_pokok', '$bonus', '$potongan', '$tanggal_pembayaran')";

            if (mysqli_query($db, $query)) {
                $sukses_message = "Data berhasil ditambahkan.";
            } else {
                $error_message = "Error pada query: " . mysqli_error($db);
            }
        }
    } else {
        $error_message = "Semua input wajib diisi.";
    }
}

// Mengambil data karyawan untuk dropdown
$data_karyawan = mysqli_query($db, "SELECT ID_Karyawan, Nama_Karyawan FROM tb_karyawan") 
    or die("Error: " . mysqli_error($db));

// Mengambil data gaji untuk tabel
$data_gaji = mysqli_query($db, "
    SELECT g.ID_Gaji, g.ID_Karyawan, g.Gaji_Pokok, g.Bonus, g.Potongan, g.Tanggal_Pembayaran, 
           k.Nama_Karyawan
    FROM tb_gaji g
    JOIN tb_karyawan k ON g.ID_Karyawan = k.ID_Karyawan
") or die("Error: " . mysqli_error($db));
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
            <h3 class="mb-4">Kelola Data Gaji</h3>
            
            <!-- Form Tambah Data -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="font-weight:bold;">Tambah Data Gaji</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_gaji" class="form-label">ID Gaji</label>
                            <input type="text" class="form-control" id="id_gaji" name="id_gaji" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_karyawan" class="form-label">ID Karyawan</label>
                            <select class="form-select" id="id_karyawan" name="id_karyawan" required>
                                <option value="" disabled selected>Pilih ID Karyawan</option>
                                <?php while ($row = mysqli_fetch_assoc($data_karyawan)): ?>
                                    <option value="<?= htmlspecialchars($row['ID_Karyawan']) ?>">
                                        <?= htmlspecialchars($row['ID_Karyawan']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                            <input type="text" class="form-control" id="gaji_pokok" name="gaji_pokok" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="bonus" class="form-label">Bonus</label>
                            <input type="text" class="form-control" id="bonus" name="bonus" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="potongan" class="form-label">Potongan</label>
                            <input type="text" class="form-control" id="potongan" name="potongan" required oninput="formatRupiah(this)">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
                        </div>
                        <?php if ($sukses_message): ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($sukses_message); ?>
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

            <!-- Tabel Data Gaji -->
            <h3 class="mb-3">Daftar Gaji</h3>
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Gaji</th>
                            <th>ID Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Bonus</th>
                            <th>Potongan</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data_gaji)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['ID_Gaji']); ?></td>
                                <td><?= htmlspecialchars($row['ID_Karyawan']); ?></td>
                                <td><?= htmlspecialchars($row['Nama_Karyawan']); ?></td>
                                <td>Rp <?= number_format($row['Gaji_Pokok'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['Bonus'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['Potongan'], 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($row['Tanggal_Pembayaran']); ?></td>
                                <td>
                                    <a href="includes/edit_gaji.php?id=<?= urlencode($row['ID_Gaji']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete('<?= $row['ID_Gaji']; ?>')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '');
            if (value) {
                let rupiah = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = rupiah;
            }
        }

        function confirmDelete(id) {
            if (confirm("Yakin ingin menghapus data?")) {
                window.location.href = "includes/delete_gaji.php?id=" + id;
            }
        }
    </script>
</body>
</html>