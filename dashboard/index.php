<?php
    include '../service/database.php';
    session_start();

    if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
        header("Location: ../login.php"); // Redirect ke halaman login
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #carouselExample {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px; 
            overflow: hidden; 
        }

        #carouselExample:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            transition: box-shadow 0.3s ease; 
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.01);
        }
    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif; background-color:#fff;">
    <?php include 'includes/header.php'; ?>
    <div class="d-flex" style="padding-top: 70px;">
        <?php include 'includes/sidebar.php'; ?>
        <main class="p-4" style="margin-left: 25%; width: 75%;">
            <!-- Carousel -->
            <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/cr1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/img12.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/img13.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Cetak Slip Gaji</h5>
                            <p class="card-text">Klik untuk mencetak slip gaji karyawan.</p>
                            <a href="../datacetakgaji" class="btn btn-secondary">Cetak</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Cetak Slip Gaji</h5>
                            <p class="card-text">Klik untuk mencetak slip gaji karyawan.</p>
                            <a href="../datacetakgaji" class="btn btn-secondary">Cetak</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>