<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
include "baglan.php";

$kullanici_id = $_SESSION["kullanici_id"];
$mesaj = "";
$malzeme_turu = isset($_GET["tur"]) ? $_GET["tur"] : "";
$bugun = date("Y-m-d");

// Referans verileri çek
$kumas_cinsleri = $baglanti->query("SELECT kumas_cinsi FROM kumas_cinsleri");
// İplik türlerini iplik_alim tablosundan benzersiz olarak çek
$iplik_turleri = $baglanti->query("SELECT DISTINCT iplik_turu FROM iplik_alim ORDER BY iplik_turu");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $aciklama = $_POST["aciklama"];
    
    switch ($malzeme_turu) {
        case "iplik":
            $iplik_turu = $_POST["iplik_turu"];
            $iplik_rengi = $_POST["iplik_rengi"];
            $kilo = $_POST["kilo"];
            $sql = "INSERT INTO iplik (tarih, iplik_turu, iplik_rengi, kilo, aciklama, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssssi", $tarih, $iplik_turu, $iplik_rengi, $kilo, $aciklama, $kullanici_id);
            break;
        case "cig_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $metre = $_POST["metre"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "INSERT INTO cig_kumas (tarih, kumas_cinsi, metre, en_mt, gr_metre, aciklama, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("ssdddsi", $tarih, $kumas_cinsi, $metre, $en_mt, $gr_metre, $aciklama, $kullanici_id);
            break;
        case "alpaka_kumas":
        case "kristal_saten_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $renk = $_POST["renk"];
            $metre = $_POST["metre"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "INSERT INTO $malzeme_turu (tarih, kumas_cinsi, renk, metre, en_mt, gr_metre, aciklama, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssdddsi", $tarih, $kumas_cinsi, $renk, $metre, $en_mt, $gr_metre, $aciklama, $kullanici_id);
            break;
        case "zefir_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $renk = $_POST["renk"];
            $metre = $_POST["metre"];
            $kare_ebati_cm = $_POST["kare_ebati_cm"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "INSERT INTO zefir_kumas (tarih, kumas_cinsi, renk, metre, kare_ebati_cm, en_mt, gr_metre, aciklama, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssddddsi", $tarih, $kumas_cinsi, $renk, $metre, $kare_ebati_cm, $en_mt, $gr_metre, $aciklama, $kullanici_id);
            break;
        default:
            $mesaj = "<div class='alert alert-danger'>Geçersiz malzeme türü!</div>";
            break;
    }

    if (isset($stmt) && $stmt->execute()) {
        $mesaj = "<div class='alert alert-success'>✅ Kayıt başarılı! <a href='stok_listele.php?tur=$malzeme_turu' class='alert-link'>Listeyi Gör</a></div>";
    } elseif (!isset($mesaj)) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: " . $stmt->error . "</div>";
    }
    if (isset($stmt)) $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Girişi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Stok Girişi</h2>

    <?php if ($mesaj) echo $mesaj; ?>

    <div class="mb-4">
        <label class="form-label">Malzeme Türü Seç</label>
        <select class="form-select" onchange="window.location.href='stok_ekle.php?tur='+this.value">
            <option value="">Seçiniz</option>
            <option value="iplik" <?php if ($malzeme_turu == "iplik") echo "selected"; ?>>İplik</option>
            <option value="cig_kumas" <?php if ($malzeme_turu == "cig_kumas") echo "selected"; ?>>Çiğ Kumaş</option>
            <option value="alpaka_kumas" <?php if ($malzeme_turu == "alpaka_kumas") echo "selected"; ?>>Alpaka Kumaş</option>
            <option value="zefir_kumas" <?php if ($malzeme_turu == "zefir_kumas") echo "selected"; ?>>Zefir Kumaş</option>
            <option value="kristal_saten_kumas" <?php if ($malzeme_turu == "kristal_saten_kumas") echo "selected"; ?>>Saten Kumaş</option>
        </select>
    </div>

    <?php if ($malzeme_turu): ?>
        <form method="POST" class="border p-4 rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="tarih" class="form-label">Tarih</label>
                 <input type="date" id="tarih" name="tarih" class="form-control" value="<?= htmlspecialchars($bugun) ?>" required>
            </div>

            <?php if ($malzeme_turu == "iplik"): ?>
                <div class="mb-3">
                    <label for="iplik_turu" class="form-label">İplik Türü</label>
                    <select id="iplik_turu" name="iplik_turu" class="form-select" required>
                        <option value="">Seçiniz</option>
                        <?php while ($satir = $iplik_turleri->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($satir["iplik_turu"]); ?>"><?php echo htmlspecialchars($satir["iplik_turu"]); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="iplik_rengi" class="form-label">İplik Rengi</label>
                    <input type="text" id="iplik_rengi" name="iplik_rengi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kilo" class="form-label">Kilo</label>
                    <input type="number" step="0.01" id="kilo" name="kilo" class="form-control" required>
                </div>
            <?php elseif ($malzeme_turu == "cig_kumas"): ?>
                <div class="mb-3">
                    <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
                    <select id="kumas_cinsi" name="kumas_cinsi" class="form-select" required>
                        <option value="">Seçiniz</option>
                        <?php while ($satir = $kumas_cinsleri->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($satir["kumas_cinsi"]); ?>"><?php echo htmlspecialchars($satir["kumas_cinsi"]); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="metre" class="form-label">Metre</label>
                    <input type="number" step="0.01" id="metre" name="metre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="en_mt" class="form-label">En (mt)</label>
                    <input type="number" step="0.01" id="en_mt" name="en_mt" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gr_metre" class="form-label">Gr/Metre</label>
                    <input type="number" step="0.01" id="gr_metre" name="gr_metre" class="form-control" required>
                </div>
            <?php elseif ($malzeme_turu == "zefir_kumas"): ?>
                <div class="mb-3">
                    <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
                    <select id="kumas_cinsi" name="kumas_cinsi" class="form-select" required>
                        <option value="">Seçiniz</option>
                        <?php while ($satir = $kumas_cinsleri->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($satir["kumas_cinsi"]); ?>"><?php echo htmlspecialchars($satir["kumas_cinsi"]); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="renk" class="form-label">Renk</label>
                    <input type="text" id="renk" name="renk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="metre" class="form-label">Metre</label>
                    <input type="number" step="0.01" id="metre" name="metre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kare_ebati_cm" class="form-label">Kare Ebatı (cm)</label>
                    <input type="number" step="0.01" id="kare_ebati_cm" name="kare_ebati_cm" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="en_mt" class="form-label">En (mt)</label>
                    <input type="number" step="0.01" id="en_mt" name="en_mt" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gr_metre" class="form-label">Gr/Metre</label>
                    <input type="number" step="0.01" id="gr_metre" name="gr_metre" class="form-control" required>
                </div>
            <?php else: // alpaka_kumas, kristal_saten_kumas ?>
                <div class="mb-3">
                    <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
                    <select id="kumas_cinsi" name="kumas_cinsi" class="form-select" required>
                        <option value="">Seçiniz</option>
                        <?php while ($satir = $kumas_cinsleri->fetch_assoc()) { ?>
                            <option value="<?php echo htmlspecialchars($satir["kumas_cinsi"]); ?>"><?php echo htmlspecialchars($satir["kumas_cinsi"]); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="renk" class="form-label">Renk</label>
                    <input type="text" id="renk" name="renk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="metre" class="form-label">Metre</label>
                    <input type="number" step="0.01" id="metre" name="metre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="en_mt" class="form-label">En (mt)</label>
                    <input type="number" step="0.01" id="en_mt" name="en_mt" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="gr_metre" class="form-label">Gr/Metre</label>
                    <input type="number" step="0.01" id="gr_metre" name="gr_metre" class="form-control" required>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="aciklama" class="form-label">Açıklama</label>
                <textarea id="aciklama" name="aciklama" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Stoğa Ekle</button>
            <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>
        </form>
    <?php endif; ?>
</body>
</html>