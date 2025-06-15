<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
include "baglan.php";

$kullanici_id = $_SESSION["kullanici_id"];
$iplikler = $baglanti->query("SELECT id, iplik_turu, tarih FROM iplik_alim");
$kumas_sorgu = "SELECT * FROM kumas_cinsleri";
$kumas_sonuc = $baglanti->query($kumas_sorgu);
$bugun = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $atki_iplik_id = $_POST["atki_iplik_id"];
    $cozgu_iplik_id = $_POST["cozgu_iplik_id"];
    $kumas_cinsleri_id = $_POST["kumas_cinsi"];
    $miktar_kg = $_POST["miktar_kg"];
    $ucret_tl = $_POST["ucret_tl"];
    $aciklama = $_POST["aciklama"];

    // kumas_cinsleri_id'nin geçerli olduğunu kontrol et
    $kumas_kontrol = $baglanti->prepare("SELECT id FROM kumas_cinsleri WHERE id = ?");
    $kumas_kontrol->bind_param("i", $kumas_cinsleri_id);
    $kumas_kontrol->execute();
    $kumas_kontrol->store_result();

    // Atkı ve çözgü iplik ID'lerinin geçerli olduğunu kontrol et
    $iplik_kontrol = $baglanti->prepare("SELECT id FROM iplik_alim WHERE id = ?");
    $iplik_kontrol->bind_param("i", $atki_iplik_id);
    $iplik_kontrol->execute();
    $iplik_kontrol->store_result();
    $atki_gecerli = $iplik_kontrol->num_rows > 0;

    $iplik_kontrol->bind_param("i", $cozgu_iplik_id);
    $iplik_kontrol->execute();
    $iplik_kontrol->store_result();
    $cozgu_gecerli = $iplik_kontrol->num_rows > 0;

    if ($kumas_kontrol->num_rows == 0) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: Geçersiz kumaş cinsi seçildi.</div>";
    } elseif (!$atki_gecerli || !$cozgu_gecerli) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: Geçersiz iplik seçildi.</div>";
    } else {
        // Dokuma kaydını ekle
        $sql = "INSERT INTO dokuma (tarih, iplik_id, cozgu_iplik_id, kumas_cinsleri_id, miktar_kg, ucret_tl, aciklama, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("siiiddsi", $tarih, $atki_iplik_id, $cozgu_iplik_id, $kumas_cinsleri_id, $miktar_kg, $ucret_tl, $aciklama, $kullanici_id);

        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>✅ Dokuma kaydı başarılı! <a href='dokuma_listele.php' class='alert-link'>Listeyi Gör</a></div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>❌ Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $kumas_kontrol->close();
    $iplik_kontrol->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Dokuma Kaydı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">Dokuma Kaydı Ekle</h2>

  <?php if (isset($mesaj)) echo $mesaj; ?>

  <form method="POST" class="border p-4 rounded shadow-sm bg-light">
    <div class="mb-3">
      <label for="tarih" class="form-label">Tarih</label>
      <input type="date" id="tarih" name="tarih" class="form-control" value="<?= htmlspecialchars($bugun) ?>" required>
    </div>

    <div class="mb-3">
      <label for="atki_iplik_id" class="form-label">Atkı İpliği</label>
      <select id="atki_iplik_id" name="atki_iplik_id" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php
        $iplikler->data_seek(0);
        while ($satir = $iplikler->fetch_assoc()) { ?>
          <option value="<?php echo $satir["id"]; ?>">
            <?php echo htmlspecialchars($satir["iplik_turu"] . " (" . $satir["tarih"] . ")"); ?>
          </option>
        <?php } $iplikler->data_seek(0); ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="cozgu_iplik_id" class="form-label">Çözgü İpliği</label>
      <select id="cozgu_iplik_id" name="cozgu_iplik_id" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php
        $iplikler->data_seek(0);
        while ($satir = $iplikler->fetch_assoc()) { ?>
          <option value="<?php echo $satir["id"]; ?>">
            <?php echo htmlspecialchars($satir["iplik_turu"] . " (" . $satir["tarih"] . ")"); ?>
          </option>
        <?php } $iplikler->data_seek(0); ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
      <select name="kumas_cinsi" id="kumas_cinsi" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php
        $kumas_sonuc->data_seek(0);
        while($kumas = $kumas_sonuc->fetch_assoc()): ?>
          <option value="<?= $kumas['id'] ?>"><?= htmlspecialchars($kumas['kumas_cinsi']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="miktar_kg" class="form-label">Miktar (kg)</label>
      <input type="number" step="0.01" id="miktar_kg" name="miktar_kg" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="ucret_tl" class="form-label">Ücret (₺)</label>
      <input type="number" step="0.01" id="ucret_tl" name="ucret_tl" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="aciklama" class="form-label">Açıklama</label>
      <textarea id="aciklama" name="aciklama" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>
  </form>

</body>
</html>