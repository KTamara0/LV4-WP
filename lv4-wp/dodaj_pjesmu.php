<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['naslov']) || empty($_POST['izvodjac'])) {
        die("Naziv i izvođač su obvezna polja!");
    }

    $godina = (int)$_POST['godina'];
    if ($godina < 1900 || $godina > 2026) { 
        die("Neispravna godina izdanja!");
    }

    $naslov = mysqli_real_escape_string($con, $_POST['naslov']);
    $izvodjac = mysqli_real_escape_string($con, $_POST['izvodjac']);
    $bpm = (int)$_POST['bpm'];

    $sql = "INSERT INTO pjesme (naslov, izvodjac, godina, bpm) VALUES ('$naslov', '$izvodjac', $godina, $bpm)";
    mysqli_query($con, $sql);
    header("Location: index.php");
}
?>