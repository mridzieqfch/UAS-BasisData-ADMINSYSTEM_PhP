<?php
    include "service/database.php";
    session_start();

    if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
        header("Location: dashboard/index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PT. Danta Persero</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1>Sistem Penggajian</h1>
            <p>Kelola data karyawan dan sistem penggajian dengan efisiensi tinggi. Login untuk mulai!</p>
            <a href="login.php" class="btn btn-custom">Log In</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Sistem Admin PT. Danta | <a href="#">UAS Basis Data</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>