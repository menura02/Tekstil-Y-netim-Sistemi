<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
include "baglan.php";

// Kullanıcı ID'sini oturumdan al
$kullanici_id = $_SESSION["kullanici_id"] ?? null;
$bugun = date("Y-m-d");

// Form gönderildiyse işle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $iplik_turu = $_POST["iplik_turu"];
    $miktar_kg = $_POST["miktar_kg"];
    $fiyat_tl = $_POST["fiyat_tl"];
    $aciklama = $_POST["aciklama"];

    $sql = "INSERT INTO iplik_alim (tarih, iplik_turu, miktar_kg, fiyat_tl, aciklama, user_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("ssddsi", $tarih, $iplik_turu, $miktar_kg, $fiyat_tl, $aciklama, $kullanici_id);

    if ($stmt->execute()) {
        $mesaj = "<div class='alert alert-success'>✅ Kayıt başarılı! <a href='iplik_listele.php' class='alert-link'>Listeyi Gör</a></div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>İplik Alım Kaydı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">İplik Alım Kaydı</h2>

  <?php if (isset($mesaj)) echo $mesaj; ?>

  <form method="POST" class="border p-4 rounded shadow-sm bg-light">
    <div class="mb-3">
      <label for="tarih" class="form-label">Tarih</label>
      <input type="date" id="tarih" name="tarih" class="form-control" value="<?= htmlspecialchars($bugun) ?>" required>
    </div>

    <div class="mb-3">
      <label for="iplik_turu" class="form-label">İplik Türü</label>
      <input type="text" id="iplik_turu" name="iplik_turu" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="miktar_kg" class="form-label">Miktar (kg)</label>
      <input type="number" step="0.01" id="miktar_kg" name="miktar_kg" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="fiyat_tl" class="form-label">Fiyat ($)</label>
      <input type="number" step="0.01" id="fiyat_tl" name="fiyat_tl" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="aciklama" class="form-label">Açıklama</label>
      <textarea id="aciklama" name="aciklama" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Kaydı Ekle</button>
    <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>
  </form>

</body>
</html>
