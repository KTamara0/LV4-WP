<?php
session_start();
include('db.php');

if (isset($_GET['id'])) {
    $stavka_id = (int)$_GET['id'];
    $id_liste = (int)$_GET['vidi_listu'];

    mysqli_query($con, "DELETE FROM stavke_playliste WHERE id = $stavka_id");
    header("Location: index.php?vidi_listu=" . $id_liste);
}
exit();
?>