<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $stavka_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM planirani_izleti WHERE id = $stavka_id AND id_korisnik = $user_id";

    if (mysqli_query($con, $sql)) {
        header("Location: index.php?status=obrisano");
        exit();
    } else {
        echo "Greška pri brisanju: " . mysqli_error($con);
    }
} else {
    header("Location: index.php");
    exit();
}
?>