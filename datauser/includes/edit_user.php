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
    $result = mysqli_query($db, "SELECT * FROM tb_users WHERE username = '$id'") or die(mysqli_error($db));
    $user = mysqli_fetch_assoc($result);

    // Jika form disubmit, update data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Update query
        $query = "UPDATE tb_users SET username = '$username', password = '$password' WHERE username = '$id'";
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
    <title>Edit User</title>
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
                    <a href="../" class="nav-link">
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
            <h3 class="mb-4">Edit Data User</h3>

            <!-- Menampilkan alert jika update berhasil -->
            <?php if (isset($_SESSION['update_success'])): ?>
                <div class="alert alert-success" role="alert">
                    Data admin berhasil diupdate.
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
                    <h5 class="card-title mb-3" style="font-weight:bold;">Edit User</h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
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