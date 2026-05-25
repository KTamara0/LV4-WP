<?php
$con = mysqli_connect("localhost", "root", "", "glazba_db");

if (mysqli_connect_errno()) {
    echo "Neuspješno povezivanje s MySQL-om: " . mysqli_connect_error();
    die();
}
?>