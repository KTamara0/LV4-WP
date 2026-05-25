<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['upload'])) {
    $naslov = mysqli_real_escape_string($con, $_POST['naslov_slike']);
    
    if ($_FILES['slika_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['slika_file'];
        $ekstenzija = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $dozvoljeni = ['jpg', 'jpeg', 'png'];
        $max_size = 5 * 1024 * 1024; 

        if (!in_array($ekstenzija, $dozvoljeni)) {
            echo "<script>alert('Greška: Samo JPG i PNG!');</script>";
        } elseif ($file['size'] > $max_size) {
            echo "<script>alert('Greška: Slika je prevelika!');</script>";
        } else {
            if (!is_dir('slike')) {
                mkdir('slike', 0777, true);
            }

            $novo_ime = uniqid() . "." . $ekstenzija;
            $putanja = "slike/" . $novo_ime;

            if (move_uploaded_file($file['tmp_name'], $putanja)) {
                $sql = "INSERT INTO slike (naslov, putanja_slike, tip) VALUES ('$naslov', '$putanja', 'lokalno')";
                if (mysqli_query($con, $sql)) {
                    echo "<script>alert('Slika uspješno dodana!'); window.location.href='slike.php';</script>";
                } else {
                    echo "Greška u bazi: " . mysqli_error($con);
                }
            } else {
                echo "Greška: Server ne dopušta spremanje u folder.";
            }
        }
    }
}

$moj_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Galerija">
        <meta name="author" content="Tamara">
        <link rel="stylesheet" href="public/style/style_slike.css">
        <title>Galerija</title>
    </head>
    <body>
        <input type="checkbox" id="menu-toggle" class="menu-toggle" aria-hidden="true">
        <header role="banner">
            <div class="header-container">
                <h1><span class="accent">PHOTO</span><small>BOOTH</small></h1>
                
                <div class="menu-wrapper">
                    <label class="menu-btn">MENU ☰</label>
                    <nav role="navigation" aria-label="Glavni izbornik">
                        <ul>
                            <li><a href="index.php">Početna (PHP)</a></li>
                            <li><a href="slike.php">Galerija (PHP)</a></li>
                            <li><a href="index.html">Stari Index (HTML)</a></li>
                            <li><a href="slike.html">Stara Galerija (HTML)</a></li>
                            <li><a href="logout.php" style="color: #ff4444;">Odjavi se</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <h2 style="text-align:center; color: #fff; margin-top: 40px; font-family: sans-serif; letter-spacing: 2px;">ARHIVA FOTOGRAFIJA (LV1 & LV2)</h2>
        
        <section class="galerija"> 
            <?php for ($i = 1; $i <= 16; $i++) { ?>
            <figure class="galerija_slika">
                <a class="image-popup-vertical-fit" href="#img_old<?php echo $i; ?>" title="<?php echo $i; ?>.jpg">
                    <img src="https://unsplash.it/300/200?random=<?php echo $i; ?>" alt="Slika <?php echo $i; ?>" loading="lazy">
                </a>
                <figcaption>Slika <?php echo $i; ?></figcaption>
                <div class="lightbox" id="img_old<?php echo $i; ?>">
                    <a href="#" class="close">&times;</a>
                    <img src="https://unsplash.it/300/200?random=<?php echo $i; ?>" alt="Slika <?php echo $i; ?> velika">
                </div>
            </figure>
            <?php } ?>
        </section>

        <hr style="border: 0; height: 1px; background: #30363d; margin: 60px 20px;">

        <h2 style="text-align:center; color: #00d4ff; font-family: sans-serif; letter-spacing: 2px;">SUSTAV ZA OCJENJIVANJE (LV4)</h2>
        
        <section style="text-align: center; padding: 25px; background: #161b22; border: 1px solid #30363d; border-radius: 10px; max-width: 600px; margin: 30px auto; color: white;">
            <h3 style="margin-top: 0; color: #58a6ff; font-size: 1.1rem; margin-bottom: 15px;">Dodaj novu fotografiju u sustav ocjenjivanja</h3>
            <form method="POST" action="slike.php" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px; align-items: center;">
                <input type="text" name="naslov_slike" placeholder="Naslov nove fotografije" required 
                       style="width: 80%; padding: 8px; background: #0d1117; border: 1px solid #30363d; color: white; border-radius: 5px;">
                <input type="file" name="slika_file" required style="color: #8b949e;">
                <button type="submit" name="upload" style="background: #238636; color: white; border: none; font-weight: bold; padding: 10px 30px; border-radius: 5px; cursor: pointer;">DODAJ SLIKU</button>
            </form>
        </section>

        <section class="galerija"> 
            <?php
            $rezultat_slika = mysqli_query($con, "SELECT * FROM slike");
            if (mysqli_num_rows($rezultat_slika) > 0) {
                while ($slika = mysqli_fetch_assoc($rezultat_slika)) {
                    $slika_id = $slika['id'];
                    
                    $res_avg = mysqli_query($con, "SELECT AVG(ocjena) as prosjek, COUNT(*) as broj FROM ocjene WHERE id_slika = $slika_id");
                    $podaci_ocjena = mysqli_fetch_assoc($res_avg);
                    $prosjek = $podaci_ocjena['prosjek'] ? round($podaci_ocjena['prosjek'], 1) : "0.0";

                    $check_user_rating = mysqli_query($con, "SELECT ocjena FROM ocjene WHERE id_slika = $slika_id AND id_korisnik = $moj_id");
                    $moja_stara_ocjena = mysqli_fetch_assoc($check_user_rating);
                ?>
                    <figure class="galerija_slika">
                        <a href="#img_new<?php echo $slika_id; ?>">
                            <img src="<?php echo $slika['putanja_slike']; ?>" alt="Slika">
                        </a>
                        
                        <figcaption style="padding-bottom: 5px;">
                            <strong><?php echo htmlspecialchars($slika['naslov']); ?></strong><br>
                            <span style="color: #ffcc00; font-size: 1.1rem;">⭐ <?php echo $prosjek; ?></span> 
                            <span style="color: #8b949e; font-size: 0.8rem;">(<?php echo $podaci_ocjena['broj']; ?> glasova)</span>
                            
                            <div id="moj-status-<?php echo $slika_id; ?>" style="color: #00d4ff; font-size: 0.85rem; margin-top: 5px; min-height: 18px;">
                                <?php if ($moja_stara_ocjena) echo "Tvoja ocjena: " . $moja_stara_ocjena['ocjena'] . " ⭐"; else echo "Nisi ocijenila"; ?>
                            </div>
                        </figcaption>

                        <form class="ajax-rating-form" style="display: flex; gap: 4px; padding: 8px; background: rgba(0,0,0,0.2); border-top: 1px solid #21262d;">
                            <input type="hidden" name="id_slika" value="<?php echo $slika_id; ?>">
                            <select name="rating" style="flex: 1; background: #0d1117; color: #fff; border: 1px solid #30363d; padding: 4px; border-radius: 4px; outline: none;">
                                <option value="5" <?php if($moja_stara_ocjena && $moja_stara_ocjena['ocjena'] == 5) echo 'selected'; ?>>5 ⭐</option>
                                <option value="4" <?php if($moja_stara_ocjena && $moja_stara_ocjena['ocjena'] == 4) echo 'selected'; ?>>4 ⭐</option>
                                <option value="3" <?php if($moja_stara_ocjena && $moja_stara_ocjena['ocjena'] == 3) echo 'selected'; ?>>3 ⭐</option>
                                <option value="2" <?php if($moja_stara_ocjena && $moja_stara_ocjena['ocjena'] == 2) echo 'selected'; ?>>2 ⭐</option>
                                <option value="1" <?php if($moja_stara_ocjena && $moja_stara_ocjena['ocjena'] == 1) echo 'selected'; ?>>1 ⭐</option>
                            </select>
                            <button type="button" class="btn-ocijeni" style="background: #00d4ff; color: #000; border: none; font-weight: bold; cursor: pointer; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem;">OK</button>
                        </form>

                        <div class="lightbox" id="img_new<?php echo $slika_id; ?>">
                            <a href="#" class="close">&times;</a>
                            <img src="<?php echo $slika['putanja_slike']; ?>" alt="Velika slika">
                        </div>
                    </figure>
                <?php 
                } 
            } else {
                echo "<p style='color: #8b949e; grid-column: 1/-1; text-align: center; padding: 30px;'>Još nema dodanih slika za ocjenjivanje. Dodaj prvu sliku iznad!</p>";
            }
            ?>
        </section>

        <footer>
            <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
        </footer>

        <script>
        document.querySelectorAll('.btn-ocijeni').forEach(button => {
            button.addEventListener('click', function() {
                const forma = this.closest('.ajax-rating-form');
                const idSlika = forma.querySelector('input[name="id_slika"]').value;
                const ocjena = forma.querySelector('select[name="rating"]').value;
                const statusDiv = document.getElementById('moj-status-' + idSlika);

                const formData = new FormData();
                formData.append('id_slika', idSlika);
                formData.append('ocjena', ocjena);

                fetch('obradi_ocjenu.php', { method: 'POST', body: formData })
                .then(r => r.text())
                .then(data => {
                    if(data.trim() === "Uspješno") {
                        statusDiv.innerHTML = "Tvoja ocjena: " + ocjena + " ⭐";
                        statusDiv.style.fontWeight = "bold";
                        
                        window.location.reload();
                    }
                });
            });
        });
        </script>
    </body>
</html>