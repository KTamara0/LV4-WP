<?php
session_start();
include('db.php');

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id_liste = (int)$_GET['id'];
    $u_id = $_SESSION['user_id'];
    $sql = "DELETE FROM spremljene_playliste WHERE id = $id_liste AND id_korisnik = $u_id";
    
    if (mysqli_query($con, $sql)) {
        header("Location: index.php?status=obrisano");
    } else {
        echo "Greška pri brisanju: " . mysqli_error($con);
    }
} else {
    header("Location: index.php");
}
exit();
?>