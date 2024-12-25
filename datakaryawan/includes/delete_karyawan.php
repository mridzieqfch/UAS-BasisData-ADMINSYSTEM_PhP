<?php
include '../../service/database.php';
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id_karyawan = mysqli_real_escape_string($db, $_GET['id']); // Menghindari SQL Injection

    // Query to delete the employee from the database
    $query = "DELETE FROM tb_karyawan WHERE ID_Karyawan = '$id_karyawan'";

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='../index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    header("Location: ../index.php");
    exit;
}