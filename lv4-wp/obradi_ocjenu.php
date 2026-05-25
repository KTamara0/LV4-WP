<?php
session_start();
include('db.php');

if (isset($_POST['id_slika']) && isset($_POST['ocjena']) && isset($_SESSION['user_id'])) {
    $id_slika = (int)$_POST['id_slika'];
    $ocjena = (int)$_POST['ocjena'];
    $id_korisnik = $_SESSION['user_id'];
    $sql = "INSERT INTO ocjene (id_korisnik, id_slika, ocjena) 
            VALUES ($id_korisnik, $id_slika, $ocjena) 
            ON DUPLICATE KEY UPDATE ocjena = $ocjena";
    
    if (mysqli_query($con, $sql)) {
        echo "Uspješno"; 
    } else {
        echo "Greška: " . mysqli_error($con);
    }
} else {
    echo "Nedostaju podaci";
}
exit();
?>