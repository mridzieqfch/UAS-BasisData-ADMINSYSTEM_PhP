<?php
include '../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

$sukses_message = "";
$eror_message = "";

// Menangani form input untuk menambahkan data user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Query untuk menyimpan data ke database
    // $query = "INSERT INTO tb_users (username, password) VALUES ('$username', '$password')";
    $check_query = "SELECT * FROM tb_users WHERE username = '$username'";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $eror_message = "Username '$username' sudah ada. Silakan gunakan Username yang berbeda.";
    } else {
        // Lanjutkan dengan query insert
        $query = "INSERT INTO tb_users (username, password) VALUES ('$username', '$password')";

        if (mysqli_query($db, $query)) {
            $sukses_message = "Data admin berhasil diinput";  
        } else {
            $error_message = "Gagal menambahkan data";
        }
    }
}

// Ambil data user untuk ditampilkan di tabel
$data_users = mysqli_query($db, "SELECT * FROM tb_users") or die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Admin</title>
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
            <h3 class="mb-4">Kelola Data User Admin</h3>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="font-weight:bold;">Tambah Data Admin</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <!-- Pesan sukses -->
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

            <h3 class="mb-3">Daftar User Admin</h3>
            <table class="table table-striped">
                <thead>
                    <tr >
                        <th scope="col">No</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($data_users)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>********</td>";
                        echo "<td>
                                <a href='includes/edit_user.php?id=" . $row['username'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='#' class='btn btn-danger btn-sm' onclick='confirmDelete(\"" . $row['username'] . "\")'>Hapus</a>
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
        function confirmDelete(username) {
            if (confirm("Ingin menghapus data?")) {
                window.location.href = "includes/delete_user.php?id=" + username;
            }
        }
    </script>
</body>
</html>