<?php
session_start();
include('db.php');

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id_pjesme = (int)$_GET['id'];
    $u_id = $_SESSION['user_id'];

    if (isset($_SESSION['aktivna_lista_za_uredivanje'])) {
        $id_liste = $_SESSION['aktivna_lista_za_uredivanje'];
        mysqli_query($con, "INSERT INTO stavke_playliste (id_playliste, id_pjesme) VALUES ($id_liste, $id_pjesme)");
        header("Location: index.php?vidi_listu=" . $id_liste);
    } else {
        mysqli_query($con, "INSERT INTO planirani_izleti (id_korisnik, id_pjesma) VALUES ($u_id, $id_pjesme)");
        header("Location: index.php");
    }
}
exit();
?>