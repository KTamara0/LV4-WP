<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['naziv_liste'])) {
    $u_id = $_SESSION['user_id'];
    $naziv = mysqli_real_escape_string($con, $_POST['naziv_liste']);

    mysqli_query($con, "INSERT INTO spremljene_playliste (id_korisnik, naziv_playliste) VALUES ($u_id, '$naziv')");
    $id_nove_liste = mysqli_insert_id($con);

    $trenutni_plan = mysqli_query($con, "SELECT id_pjesma FROM planirani_izleti WHERE id_korisnik = $u_id");
    while($red = mysqli_fetch_assoc($trenutni_plan)) {
        $id_pjesme = $red['id_pjesma'];
        mysqli_query($con, "INSERT INTO stavke_playliste (id_playliste, id_pjesme) VALUES ($id_nove_liste, $id_pjesme)");
    }

    mysqli_query($con, "DELETE FROM planirani_izleti WHERE id_korisnik = $u_id");

    header("Location: index.php?status=spremljeno");
    exit();
}
?>