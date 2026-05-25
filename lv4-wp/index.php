<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['spremi_pjesmu'])) {
    $naslov = mysqli_real_escape_string($con, $_POST['naslov']);
    $izvodjac = mysqli_real_escape_string($con, $_POST['izvodjac']);
    $zanr = mysqli_real_escape_string($con, $_POST['zanr']);
    $bpm = (int)$_POST['bpm'];
    $godina = (int)$_POST['godina'];
    $popularnost = (double)$_POST['popularnost']; 
    $raspolozenje = mysqli_real_escape_string($con, $_POST['raspolozenje']);

    if (empty($naslov) || empty($izvodjac)) {
        $errors[] = "Naslov i izvođač su obvezni!";
    }
    if ($godina < 1950 || $godina > 2026) {
        $errors[] = "Godina mora biti između 1900 i 2026!";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO pjesme (naslov, izvodjac, zanr, bpm, godina, popularnost, raspolozenje) 
                VALUES ('$naslov', '$izvodjac', '$zanr', $bpm, $godina, $popularnost, '$raspolozenje')";
        mysqli_query($con, $sql);
        header("Location: index.php");
        exit();
    }
}

$query = "SELECT * FROM pjesme WHERE 1=1";

// 1. Filtriranje prema izvođaču 
if (!empty($_GET['izvodjac'])) {
    $izvodjac_filter = mysqli_real_escape_string($con, $_GET['izvodjac']);
    $query .= " AND izvodjac LIKE '%$izvodjac_filter%'";
}

// 2. Filtriranje prema žanru 
if (!empty($_GET['zanr'])) {
    $zanr_filter = mysqli_real_escape_string($con, $_GET['zanr']);
    $query .= " AND zanr = '$zanr_filter'";
}

// 3. Filtriranje prema godini izdanja
if (!empty($_GET['godina'])) {
    $godina_filter = (int)$_GET['godina'];
    $query .= " AND godina = $godina_filter";
}

// 4. Filtriranje prema BPM-u (Prikazuje pjesme s BPM-om većim ili jednakim unesenom)
if (!empty($_GET['bpm'])) {
    $bpm_filter = (int)$_GET['bpm'];
    $query .= " AND bpm >= $bpm_filter";
}

// 5. Filtriranje prema raspoloženju
if (!empty($_GET['raspolozenje'])) {
    $mood_filter = mysqli_real_escape_string($con, $_GET['raspolozenje']);
    $query .= " AND raspolozenje LIKE '%$mood_filter%'";
}

$query .= " ORDER BY godina DESC";

$rezultat = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>LV3 - Glazba</title>
    <link rel="stylesheet" href="public/style/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
</head>
<body>
<header>
    <div class="header-container">
        <h1>Uživaj u glazbi!</h1>
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

<div class="analysis-section" style="margin-bottom: 30px; padding: 20px; border: 1px solid #30363d; border-radius: 8px; background: #161b22;">
    <h2 style="color: #00d4ff;">Dodaj novu pjesmu</h2>
    
    <?php if (!empty($errors)): ?>
        <div style="background: rgba(255,0,0,0.1); padding: 10px; margin-bottom: 10px; border-radius: 5px;">
            <?php foreach ($errors as $err): ?>
                <p style="color: #ff4d4d; margin: 0;"><?php echo $err; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <input type="text" name="naslov" placeholder="Naslov pjesme *" required style="width: 100%;">
            <input type="text" name="izvodjac" placeholder="Izvođač *" required style="width: 100%;">
            <input type="text" name="zanr" placeholder="Žanr">
            <input type="number" name="bpm" placeholder="BPM">
            <input type="number" name="godina" placeholder="Godina izdanja (1900-2026)">
			<input type="number" step="0.1" name="popularnost" placeholder="Popularnost (npr. 4.5)">
            <input type="text" name="raspolozenje" placeholder="Raspoloženje">
        </div>
        <button type="submit" name="spremi_pjesmu" style="margin-top: 15px; width: 100%; background: #00d4ff; color: #000;">
            SPREMI U BAZU
        </button>
    </form>
</div>
 
<div id="filteri" style="background: #161b22; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <form method="GET" action="index.php" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        
        <input type="text" name="izvodjac" placeholder="Izvođač..." value="<?php echo $_GET['izvodjac'] ?? ''; ?>">
 
    <select name="zanr">
        <option value="">Svi žanrovi</option>
        <option value="Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Rock') echo 'selected'; ?>>Rock</option>
		<option value="Hard Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Hard Rock') echo 'selected'; ?>>Hard Rock</option>
		<option value="Soft Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Soft Rock') echo 'selected'; ?>>Soft Rock</option>
		<option value="Indie Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Indie Rock') echo 'selected'; ?>>Indie Rock</option>
		<option value="Funk Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Funk Rock') echo 'selected'; ?>>Funk Rock</option>
		<option value="Garage Rock" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Garage Rock') echo 'selected'; ?>>Garage Rock</option>
        <option value="Pop" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Pop') echo 'selected'; ?>>Pop</option>
		<option value="Electropop" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Electropop') echo 'selected'; ?>>Electropop</option>
		<option value="Disco-Pop" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Disco-Pop') echo 'selected'; ?>>Disco-Pop</option>
        <option value="Jazz" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Jazz') echo 'selected'; ?>>Jazz</option>
        <option value="Hip Hop" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Hip-Hop') echo 'selected'; ?>>Hip Hop</option>
		<option value="Synthwave" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Synthwave') echo 'selected'; ?>>Synthwave</option>
		<option value="Grunge" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Grunge') echo 'selected'; ?>>Grunge</option>
		<option value="Nu Metal" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Nu Metal') echo 'selected'; ?>>Nu Metal</option>
		<option value="Thrash Metal" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Thrash Metal') echo 'selected'; ?>>Thrash Metal</option>
		<option value="Funk" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Funk') echo 'selected'; ?>>Funk</option>
		<option value="Soul" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'Soul') echo 'selected'; ?>>Soul</option>
		<option value="House" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'House') echo 'selected'; ?>>House</option>
		<option value="R&B" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'R&B') echo 'selected'; ?>>R&B</option>
		<option value="EDM" <?php if(isset($_GET['zanr']) && $_GET['zanr'] == 'EDM') echo 'selected'; ?>>EDM</option>
    </select>

	<input type="number" name="godina" placeholder="Godina..." style="width: 80px;" value="<?php echo $_GET['godina'] ?? ''; ?>">     
    <input type="number" name="bpm" placeholder="Min. BPM..." style="width: 100px;" value="<?php echo $_GET['bpm'] ?? ''; ?>">  
    <input type="text" name="raspolozenje" placeholder="Raspoloženje..." value="<?php echo $_GET['raspolozenje'] ?? ''; ?>">
    <button type="submit" style="background: #00d4ff; color: #000;">Pretraži</button>
    <a href="index.php" style="color: #ff4d4d; text-decoration: none; font-size: 0.8rem;">Očisti</a>
 
    </form>
</div>
 

 
<div class="main-wrapper" style="padding: 20px;">
    <div style="flex: 3; min-width: 300px;">
        <div class="table-wrapper"> <table id="tablicaGlazbe" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Izvođač</th>
                    <th>Žanr</th>
                    <th>BPM</th>
                    <th>Popularnost</th>
                    <th>Godina</th>
                    <th>Raspoloženje</th>
                    <th>Akcija</th>
                </tr>
            </thead>
				<tbody id="bodyTable">
					<?php 
					if ($rezultat && mysqli_num_rows($rezultat) > 0) {
						while($row = mysqli_fetch_assoc($rezultat)) {
							echo "<tr>";
							echo "<td>" . htmlspecialchars($row['naslov']) . "</td>"; 
							echo "<td>" . htmlspecialchars($row['izvodjac']) . "</td>";
							echo "<td>" . htmlspecialchars($row['zanr']) . "</td>";
							echo "<td>" . htmlspecialchars($row['bpm']) . "</td>";
							echo "<td>" . htmlspecialchars($row['popularnost']) . "</td>";
							echo "<td>" . htmlspecialchars($row['godina']) . "</td>";
							echo "<td>" . htmlspecialchars($row['raspolozenje']) . "</td>";
							echo "<td><a href='dodaj_u_playlistu.php?id=" . $row['id'] . "' class='btn-tablica'>Dodaj na listu</a></td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='8' style='text-align:center;'>Nema pronađenih pjesama.</td></tr>";
					}
					?>
				</tbody>
            </table>
        </div>
    </div>
</div>

<div class="moje-liste-kontejner" style="margin-top: 30px; background: #0d1117; padding: 25px; border-radius: 12px; border: 1px solid #30363d;">
    
    <?php
    $u_id = $_SESSION['user_id'];

    if (isset($_GET['vidi_listu'])) {
        $id_liste = (int)$_GET['vidi_listu'];
        $_SESSION['aktivna_lista_za_uredivanje'] = $id_liste;

        $res_ime = mysqli_query($con, "SELECT naziv_playliste FROM spremljene_playliste WHERE id = $id_liste");
        $podaci_liste = mysqli_fetch_assoc($res_ime);
        
        echo "<div style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;'>";
        echo "<h2 style='color: #00d4ff; margin: 0;'>Uređivanje liste: " . htmlspecialchars($podaci_liste['naziv_playliste']) . "</h2>";
        echo "<a href='index.php?stani_uredivanje=1' style='color: #ff4d4d; text-decoration: none; border: 1px solid #ff4d4d; padding: 5px 10px; border-radius: 5px;'>Završi uređivanje / Natrag</a>";
        echo "</div>";
        echo "<p style='color: #8b949e; font-size: 0.9rem;'>* Sada možeš dodavati nove pjesme iz gornje tablice direktno u ovu listu.</p>";

        $sql_prikaz = "SELECT p.naslov, p.izvodjac, sp.id as stavka_id 
                       FROM stavke_playliste sp
                       JOIN pjesme p ON sp.id_pjesme = p.id
                       WHERE sp.id_playliste = $id_liste";
    } else {
        if(isset($_GET['stani_uredivanje'])) unset($_SESSION['aktivna_lista_za_uredivanje']);
        unset($_SESSION['aktivna_lista_za_uredivanje']); 

        ?>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px; gap: 15px;">
            <h2 style="color: #58a6ff; margin: 0;">Trenutni plan (Košarica)</h2>
            <form method="POST" action="spremi_listu.php" style="display: flex; gap: 8px;">
                <input type="text" name="naziv_liste" placeholder="Naziv nove playliste..." required 
                       style="background: #1c2128; border: 1px solid #444; color: white; padding: 8px 12px; border-radius: 5px;">
                <button type="submit" style="background: #238636; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">SPREMI KAO NOVU</button>
            </form>
        </div>
        <?php
        $sql_prikaz = "SELECT p.naslov, p.izvodjac, pi.id as stavka_id 
                       FROM planirani_izleti pi
                       JOIN pjesme p ON pi.id_pjesma = p.id 
                       WHERE pi.id_korisnik = $u_id";
    }

    $rez_prikaz = mysqli_query($con, $sql_prikaz);
    ?>

    <table style="width: 100%; border-collapse: collapse; color: #c9d1d9;">
        <thead>
            <tr style="border-bottom: 2px solid #30363d;">
                <th style="padding: 12px; text-align: left;">Pjesma</th>
                <th style="padding: 12px; text-align: left;">Izvođač</th>
                <th style="padding: 12px; text-align: center;">Upravljanje</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($rez_prikaz && mysqli_num_rows($rez_prikaz) > 0) {
                while($item = mysqli_fetch_assoc($rez_prikaz)) {
                    echo "<tr style='border-bottom: 1px solid #21262d;'>";
                    echo "<td style='padding: 12px;'>" . htmlspecialchars($item['naslov']) . "</td>";
                    echo "<td style='padding: 12px;'>" . htmlspecialchars($item['izvodjac']) . "</td>";
                    
                    $link_brisanja = isset($_GET['vidi_listu']) 
                        ? "ukloni_iz_spremljene.php?id=" . $item['stavka_id'] . "&vidi_listu=" . $id_liste
                        : "ukloni_iz_playliste.php?id=" . $item['stavka_id'];

                    echo "<td style='padding: 12px; text-align: center;'>
                            <a href='$link_brisanja' style='color: #ff7b72; text-decoration: none;'>[Ukloni]</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' style='padding: 20px; text-align: center;'>Lista je prazna.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="arhiva-playlista" style="margin-top: 40px; padding: 20px; background: #161b22; border-radius: 10px; border: 1px solid #00d4ff;">
    <h2 style="color: #00d4ff;">Moja arhiva spremljenih playlista</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
        <?php
        $sql_arhiva = "SELECT * FROM spremljene_playliste WHERE id_korisnik = $u_id ORDER BY datum_kreiranja DESC";
        $rez_arhiva = mysqli_query($con, $sql_arhiva);

        if (mysqli_num_rows($rez_arhiva) > 0) {
            while($ar = mysqli_fetch_assoc($rez_arhiva)) {
                echo "<div style='background: #0d1117; padding: 15px; border-radius: 8px; border-left: 4px solid #00d4ff;'>";
                echo "<h4 style='margin: 0; color: white;'>" . htmlspecialchars($ar['naziv_playliste']) . "</h4>";
                echo "<small style='color: #8b949e;'>" . date("d.m.Y.", strtotime($ar['datum_kreiranja'])) . "</small>";
                echo "<br><a href='index.php?vidi_listu=" . $ar['id'] . "' style='color: #58a6ff; font-size: 0.8rem; text-decoration: none;'>Pregledaj pjesme →</a>";
                echo "<a href='obrisi_playlistu.php?id=" . $ar['id'] . "' 
                    onclick='return confirm(\"Jesi li sigurna da želiš obrisati cijelu playlistu?\")' 
                    style='color: #ff4d4d; font-size: 0.8rem; text-decoration: none;'>
                    [Obriši]
                </a>";
                echo "</div>";
            }
        } else {
            echo "<p style='color: #8b949e;'>Nema spremljenih playlista u arhivi.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>