<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
include "baglan.php";

$kullanici_id = $_SESSION["kullanici_id"];
$kumaslar = $baglanti->query("SELECT id, kumas_cinsi FROM kumas_cinsleri");
$bugun = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $kumas_cinsleri_id = $_POST["kumas_cinsi"];
    $renk = $_POST["renk"];
    $miktar_mt = $_POST["miktar_mt"];
    $fiyat_tl = $_POST["fiyat_tl"];
    $aciklama = $_POST["aciklama"];

    // kumas_cinsleri_id'nin geçerli olduğunu kontrol et
    $kumas_kontrol = $baglanti->prepare("SELECT id FROM kumas_cinsleri WHERE id = ?");
    $kumas_kontrol->bind_param("i", $kumas_cinsleri_id);
    $kumas_kontrol->execute();
    $kumas_kontrol->store_result();

    if ($kumas_kontrol->num_rows == 0) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: Geçersiz kumaş cinsi seçildi.</div>";
    } else {
        $sql = "INSERT INTO satis (tarih, kumas_cinsleri_id, renk, miktar_mt, fiyat_tl, aciklama, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("sisdssi", $tarih, $kumas_cinsleri_id, $renk, $miktar_mt, $fiyat_tl, $aciklama, $kullanici_id);

        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>✅ Satış kaydı başarılı! <a href='satis_listele.php' class='alert-link'>Listeyi Gör</a></div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>❌ Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $kumas_kontrol->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Satış Kaydı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">Satış Kaydı Ekle</h2>

  <?php if (isset($mesaj)) echo $mesaj; ?>

  <form method="POST" class="border p-4 rounded shadow-sm bg-light">
    <div class="mb-3">
      <label for="tarih" class="form-label">Tarih</label>
      <input type="date" id="tarih" name="tarih" class="form-control" value="<?= htmlspecialchars($bugun) ?>" required>
    </div>

    <div class="mb-3">
      <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
      <select id="kumas_cinsi" name="kumas_cinsi" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php while ($kumas = $kumaslar->fetch_assoc()) { ?>
          <option value="<?php echo $kumas["id"]; ?>">
            <?php echo htmlspecialchars($kumas["kumas_cinsi"]); ?>
          </option>
        <?php } $kumaslar->data_seek(0); ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="renk" class="form-label">Renk</label>
      <input type="text" id="renk" name="renk" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="miktar_mt" class="form-label">Miktar (metre)</label>
      <input type="number" step="0.01" id="miktar_mt" name="miktar_mt" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="fiyat_tl" class="form-label">Fiyat (₺)</label>
      <input type="number" step="0.01" id="fiyat_tl" name="fiyat_tl" class="form-control" required>
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