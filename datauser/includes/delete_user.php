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

    // Periksa apakah username valid
    $id = mysqli_real_escape_string($db, $id);

    // Hapus data user berdasarkan ID
    $query = "DELETE FROM tb_users WHERE username = '$id'";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='../index.php';</script>";
    } else {
        die("Error: " . mysqli_error($db)); // Menampilkan error jika query gagal
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>