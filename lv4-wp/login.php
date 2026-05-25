<?php
session_start();
include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, lozinka FROM korisnici WHERE korisnicko_ime = '$username'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['lozinka'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Pogrešno korisničko ime ili lozinka!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prijava - LV4</title>
    <link rel="stylesheet" href="public/style/style.css"> </head>
<body>
    <div class="login-container">
        <div class="login-box">
            <form method="POST" action="login.php">
                <h2>Prijava</h2>
                <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
                <input type="text" name="username" placeholder="Korisničko ime" required>
                <input type="password" name="password" placeholder="Lozinka" required>
                <button type="submit">Prijavi se</button>
                <p>Nemaš račun? <a href="registracija.php">Registriraj se ovdje</a></p>
            </form>
        </div>
    </div>
</body>
</html>