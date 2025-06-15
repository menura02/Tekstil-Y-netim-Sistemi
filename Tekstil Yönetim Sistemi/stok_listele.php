<?php
session_start();
if (!isset($_SESSION["kullanici"]) || $_SESSION["rol"] !== "admin") {
    echo "<div style=\"text-align: center; margin-top: 5rem; font-size: 1.5rem;\">❌ Bu sayfaya erişim yetkiniz yok.</div>";
    exit();
}

include "baglan.php";

$malzeme_turu = isset($_GET["tur"]) ? $_GET["tur"] : "";
$kullanici_id = $_SESSION["kullanici_id"];

// Silme işlemi
if (isset($_GET["sil"]) && $malzeme_turu) {
    $id = intval($_GET["sil"]);
    $stmt = $baglanti->prepare("DELETE FROM $malzeme_turu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: stok_listele.php?tur=$malzeme_turu");
    exit();
}

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guncelle_id"]) && $malzeme_turu) {
    $id = $_POST["guncelle_id"];
    $tarih = $_POST["tarih"];
    $aciklama = $_POST["aciklama"];
    
    switch ($malzeme_turu) {
        case "iplik":
            $iplik_turu = $_POST["iplik_turu"];
            $iplik_rengi = $_POST["iplik_rengi"];
            $kilo = $_POST["kilo"];
            $sql = "UPDATE iplik SET tarih=?, iplik_turu=?, iplik_rengi=?, kilo=?, aciklama=?, user_id=? WHERE id=?";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssssii", $tarih, $iplik_turu, $iplik_rengi, $kilo, $aciklama, $kullanici_id, $id);
            break;
        case "cig_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $metre = $_POST["metre"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "UPDATE cig_kumas SET tarih=?, kumas_cinsi=?, metre=?, en_mt=?, gr_metre=?, aciklama=?, user_id=? WHERE id=?";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("ssdddsi", $tarih, $kumas_cinsi, $metre, $en_mt, $gr_metre, $aciklama, $kullanici_id, $id);
            break;
        case "alpaka_kumas":
        case "kristal_saten_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $renk = $_POST["renk"];
            $metre = $_POST["metre"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "UPDATE $malzeme_turu SET tarih=?, kumas_cinsi=?, renk=?, metre=?, en_mt=?, gr_metre=?, aciklama=?, user_id=? WHERE id=?";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssdddsi", $tarih, $kumas_cinsi, $renk, $metre, $en_mt, $gr_metre, $aciklama, $kullanici_id, $id);
            break;
        case "zefir_kumas":
            $kumas_cinsi = $_POST["kumas_cinsi"];
            $renk = $_POST["renk"];
            $metre = $_POST["metre"];
            $kare_ebati_cm = $_POST["kare_ebati_cm"];
            $en_mt = $_POST["en_mt"];
            $gr_metre = $_POST["gr_metre"];
            $sql = "UPDATE zefir_kumas SET tarih=?, kumas_cinsi?, renk=?, metre=?, kare_ebati_cm=?, en_mt=?, gr_metre=?, aciklama=?, user_id=? WHERE id=?";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sssddddsi", $tarih, $kumas_cinsi, $renk, $metre, $kare_ebati_cm, $en_mt, $gr_metre, $aciklama, $kullanici_id, $id);
            break;
    }
    
    $stmt->execute();
    header("Location: stok_listele.php?tur=$malzeme_turu");
    exit();
}

// Düzenlenecek kayıt
$duzenlenecek = null;
if (isset($_GET["duzenle"]) && $malzeme_turu) {
    $id = intval($_GET["duzenle"]);
    $stmt = $baglanti->prepare("SELECT * FROM $malzeme_turu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $sonuc = $stmt->get_result();
    $duzenlenecek = $sonuc->fetch_assoc();
}

// Kayıtları çek
$veriler = null;
if ($malzeme_turu) {
    $sql = "SELECT s.*, u.username FROM $malzeme_turu s 
            LEFT JOIN users u ON s.user_id = u.id 
            ORDER BY s.tarih DESC";
    $veriler = $baglanti->query($sql);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Stok Listesi</h2>

    <div class="mb-4">
        <label class="form-label">Malzeme Türü Seç</label>
        <select class="form-select" onchange="window.location.href='stok_listele.php?tur='+this.value">
            <option value="">Seçiniz</option>
            <option value="iplik" <?php if ($malzeme_turu == "iplik") echo "selected"; ?>>İplik</option>
            <option value="cig_kumas" <?php if ($malzeme_turu == "cig_kumas") echo "selected"; ?>>Çiğ Kumaş</option>
            <option value="alpaka_kumas" <?php if ($malzeme_turu == "alpaka_kumas") echo "selected"; ?>>Alpaka Kumaş</option>
            <option value="zefir_kumas" <?php if ($malzeme_turu == "zefir_kumas") echo "selected"; ?>>Zefir Kumaş</option>
            <option value="kristal_saten_kumas" <?php if ($malzeme_turu == "kristal_saten_kumas") echo "selected"; ?>>Saten Kumaş</option>
        </select>
    </div>

    <?php if ($duzenlenecek && $malzeme_turu): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning">Stok Kaydı Güncelle</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="guncelle_id" value="<?= $duzenlenecek["id"] ?>">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tarih</label>
                            <input type="date" name="tarih" class="form-control" value="<?= $duzenlenecek["tarih"] ?>" required>
                        </div>
                        <?php if ($malzeme_turu == "iplik"): ?>
                            <div class="col-md-3">
                                <label class="form-label">İplik Türü</label>
                                <select name="iplik_turu" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $iplik_turleri = $baglanti->query("SELECT DISTINCT iplik_turu FROM iplik_alim ORDER BY iplik_turu");
                                    while ($satir = $iplik_turleri->fetch_assoc()) {
                                        $selected = $satir["iplik_turu"] == $duzenlenecek["iplik_turu"] ? "selected" : "";
                                        echo "<option value='" . htmlspecialchars($satir["iplik_turu"]) . "' $selected>" . htmlspecialchars($satir["iplik_turu"]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">İplik Rengi</label>
                                <input type="text" name="iplik_rengi" class="form-control" value="<?= htmlspecialchars($duzenlenecek["iplik_rengi"]) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kilo</label>
                                <input type="number" step="0.01" name="kilo" class="form-control" value="<?= $duzenlenecek["kilo"] ?>" required>
                            </div>
                        <?php elseif ($malzeme_turu == "cig_kumas"): ?>
                            <div class="col-md-3">
                                <label class="form-label">Kumaş Cinsi</label>
                                <select name="kumas_cinsi" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $kumas_cinsleri = $baglanti->query("SELECT kumas_cinsi FROM kumas_cinsleri");
                                    while ($satir = $kumas_cinsleri->fetch_assoc()) {
                                        $selected = $satir["kumas_cinsi"] == $duzenlenecek["kumas_cinsi"] ? "selected" : "";
                                        echo "<option value='" . htmlspecialchars($satir["kumas_cinsi"]) . "' $selected>" . htmlspecialchars($satir["kumas_cinsi"]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metre</label>
                                <input type="number" step="0.01" name="metre" class="form-control" value="<?= $duzenlenecek["metre"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">En (mt)</label>
                                <input type="number" step="0.01" name="en_mt" class="form-control" value="<?= $duzenlenecek["en_mt"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gr/Metre</label>
                                <input type="number" step="0.01" name="gr_metre" class="form-control" value="<?= $duzenlenecek["gr_metre"] ?>" required>
                            </div>
                        <?php elseif ($malzeme_turu == "zefir_kumas"): ?>
                            <div class="col-md-3">
                                <label class="form-label">Kumaş Cinsi</label>
                                <select name="kumas_cinsi" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $kumas_cinsleri = $baglanti->query("SELECT kumas_cinsi FROM kumas_cinsleri");
                                    while ($satir = $kumas_cinsleri->fetch_assoc()) {
                                        $selected = $satir["kumas_cinsi"] == $duzenlenecek["kumas_cinsi"] ? "selected" : "";
                                        echo "<option value='" . htmlspecialchars($satir["kumas_cinsi"]) . "' $selected>" . htmlspecialchars($satir["kumas_cinsi"]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Renk</label>
                                <input type="text" name="renk" class="form-control" value="<?= htmlspecialchars($duzenlenecek["renk"]) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metre</label>
                                <input type="number" step="0.01" name="metre" class="form-control" value="<?= $duzenlenecek["metre"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kare Ebatı (cm)</label>
                                <input type="number" step="0.01" name="kare_ebati_cm" class="form-control" value="<?= $duzenlenecek["kare_ebati_cm"] ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">En (mt)</label>
                                <input type="number" step="0.01" name="en_mt" class="form-control" value="<?= $duzenlenecek["en_mt"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gr/Metre</label>
                                <input type="number" step="0.01" name="gr_metre" class="form-control" value="<?= $duzenlenecek["gr_metre"] ?>" required>
                            </div>
                        <?php else: // alpaka_kumas, kristal_saten_kumas ?>
                            <div class="col-md-3">
                                <label class="form-label">Kumaş Cinsi</label>
                                <select name="kumas_cinsi" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php
                                    $kumas_cinsleri = $baglanti->query("SELECT kumas_cinsi FROM kumas_cinsleri");
                                    while ($satir = $kumas_cinsleri->fetch_assoc()) {
                                        $selected = $satir["kumas_cinsi"] == $duzenlenecek["kumas_cinsi"] ? "selected" : "";
                                        echo "<option value='" . htmlspecialchars($satir["kumas_cinsi"]) . "' $selected>" . htmlspecialchars($satir["kumas_cinsi"]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Renk</label>
                                <input type="text" name="renk" class="form-control" value="<?= htmlspecialchars($duzenlenecek["renk"]) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metre</label>
                                <input type="number" step="0.01" name="metre" class="form-control" value="<?= $duzenlenecek["metre"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">En (mt)</label>
                                <input type="number" step="0.01" name="en_mt" class="form-control" value="<?= $duzenlenecek["en_mt"] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gr/Metre</label>
                                <input type="number" step="0.01" name="gr_metre" class="form-control" value="<?= $duzenlenecek["gr_metre"] ?>" required>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" class="form-control"><?= htmlspecialchars($duzenlenecek["aciklama"]) ?></textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary">Güncelle</button>
                        <a href="stok_listele.php?tur=<?= $malzeme_turu ?>" class="btn btn-secondary ms-2">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($malzeme_turu && $veriler): ?>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Tarih</th>
                    <?php if ($malzeme_turu == "iplik"): ?>
                        <th>İplik Türü</th>
                        <th>İplik Rengi</th>
                        <th>Kilo</th>
                    <?php elseif ($malzeme_turu == "cig_kumas"): ?>
                        <th>Kumaş Cinsi</th>
                        <th>Metre</th>
                        <th>En (mt)</th>
                        <th>Gr/Metre</th>
                    <?php elseif ($malzeme_turu == "zefir_kumas"): ?>
                        <th>Kumaş Cinsi</th>
                        <th>Renk</th>
                        <th>Metre</th>
                        <th>Kare Ebatı (cm)</th>
                        <th>En (mt)</th>
                        <th>Gr/Metre</th>
                    <?php else: ?>
                        <th>Kumaş Cinsi</th>
                        <th>Renk</th>
                        <th>Metre</th>
                        <th>En (mt)</th>
                        <th>Gr/Metre</th>
                    <?php endif; ?>
                    <th>Açıklama</th>
                    <th>Ekleyen</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($satir = $veriler->fetch_assoc()): ?>
                    <tr>
                        <td><?= date("d/m/Y", strtotime($satir["tarih"])) ?></td>
                        <?php if ($malzeme_turu == "iplik"): ?>
                            <td><?= htmlspecialchars($satir["iplik_turu"]) ?></td>
                            <td><?= htmlspecialchars($satir["iplik_rengi"]) ?></td>
                            <td><?= $satir["kilo"] ?></td>
                        <?php elseif ($malzeme_turu == "cig_kumas"): ?>
                            <td><?= htmlspecialchars($satir["kumas_cinsi"]) ?></td>
                            <td><?= $satir["metre"] ?></td>
                            <td><?= $satir["en_mt"] ?></td>
                            <td><?= $satir["gr_metre"] ?></td>
                        <?php elseif ($malzeme_turu == "zefir_kumas"): ?>
                            <td><?= htmlspecialchars($satir["kumas_cinsi"]) ?></td>
                            <td><?= htmlspecialchars($satir["renk"]) ?></td>
                            <td><?= $satir["metre"] ?></td>
                            <td><?= $satir["kare_ebati_cm"] ?? '-' ?></td>
                            <td><?= $satir["en_mt"] ?></td>
                            <td><?= $satir["gr_metre"] ?></td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($satir["kumas_cinsi"]) ?></td>
                            <td><?= htmlspecialchars($satir["renk"]) ?></td>
                            <td><?= $satir["metre"] ?></td>
                            <td><?= $satir["en_mt"] ?></td>
                            <td><?= $satir["gr_metre"] ?></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($satir["aciklama"]) ?></td>
                        <td><?= htmlspecialchars($satir["username"]) ?></td>
                        <td>
                            <a href="?tur=<?= $malzeme_turu ?>&duzenle=<?= $satir["id"] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="?tur=<?= $malzeme_turu ?>&sil=<?= $satir["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="stok_ekle.php" class="btn btn-success">Yeni Stok Girişi</a>
    <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>
</body>
</html>