<?php
session_start();
include('db.php'); 

// if (!isset($_SESSION['is_admin'])) { die("Pristup odbijen"); }

if (isset($_POST['import'])) {
    $file = fopen($_FILES['csv_file']['tmp_name'], "r");
    fgetcsv($file); 

    while (($column = fgetcsv($file)) !== FALSE) {
        $naslov = mysqli_real_escape_string($con, $column[1]);
        $izvodjac = mysqli_real_escape_string($con, $column[2]);
        $zanr = mysqli_real_escape_string($con, $column[3]);
        $bpm = (int)$column[4];
        $godina = (int)$column[5];
        $raspolozenje = mysqli_real_escape_string($con, $column[7]);
        $sql = "INSERT INTO pjesme (naslov, izvodjac, zanr, bpm, godina, raspolozenje) 
                VALUES ('$naslov', '$izvodjac', '$zanr', $bpm, $godina, '$raspolozenje')";
        
        mysqli_query($con, $sql);
    }
    fclose($file);
    echo "Podaci su uspješno uvezeni!";
}
?>

<h2>Administracija - Uvoz pjesama</h2>
<form action="admin.php" method="post" enctype="multipart/form-data">
    <label>Odaberite CSV datoteku:</label>
    <input type="file" name="csv_file" accept=".csv" required>
    <button type="submit" name="import">Uvezi podatke</button>
</form>