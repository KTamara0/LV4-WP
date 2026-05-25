<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Sva polja su obvezna!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $checkUser = mysqli_query($con, "SELECT id FROM korisnici WHERE korisnicko_ime = '$username'");
        
        if (mysqli_num_rows($checkUser) > 0) {
            $error = "Korisničko ime već postoji!";
        } else {
            $sql = "INSERT INTO korisnici (korisnicko_ime, lozinka) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($con, $sql)) {
                header("Location: login.php?msg=Uspješna registracija!");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registracija - LV4</title>
    <link rel="stylesheet" href="public/style/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <form method="POST" action="registracija.php">
                <h2>Registracija</h2>
                <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
                <input type="text" name="username" placeholder="Odaberite korisničko ime" required>
                <input type="password" name="password" placeholder="Odaberite lozinku" required>
                <button type="submit">Registriraj se</button>
                <p><a href="login.php" style="color: #58a6ff;">Već imate račun? Prijavite se</a></p>
            </form>
        </div>
    </div>
</body>
</html>