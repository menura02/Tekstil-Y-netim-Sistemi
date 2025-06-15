<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
include "baglan.php";

// Oturumdan kullanıcı ID'si
$kullanici_id = $_SESSION["kullanici_id"];

// Bugünün tarihini al
$bugun = date("Y-m-d");

$kumas_sorgu = "SELECT * FROM kumas_cinsleri";
$kumas_sonuc = $baglanti->query($kumas_sorgu);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST["tarih"];
    $kumas_cinsleri_id = $_POST["kumas_cinsi"];
    $miktar_mt = $_POST["miktar_mt"];
    $renk = $_POST["renk"];
    $ucret_tl = $_POST["ucret_tl"];
    $aciklama = $_POST["aciklama"];

    // kumas_cinsleri_id'nin geçerli olduğunu kontrol et
    $kumas_kontrol = $baglanti->prepare("SELECT id FROM kumas_cinsleri WHERE id = ?");
    $kumas_kontrol->bind_param("i", $kumas_cinsleri_id);
    $kumas_kontrol->execute();
    $kumas_kontrol->store_result();

    if ($kumas_kontrol->num_rows == 0) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: Geçersiz kumaş cinsi seçildi.</div>";
    } elseif (empty($renk)) {
        $mesaj = "<div class='alert alert-danger'>❌ Hata: Renk alanı boş olamaz.</div>";
    } else {
        $sql = "INSERT INTO boya (tarih, kumas_cinsleri_id, miktar_mt, renk, ucret_tl, aciklama, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("sidsssi", $tarih, $kumas_cinsleri_id, $miktar_mt, $renk, $ucret_tl, $aciklama, $kullanici_id);

        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>✅ Boya kaydı başarılı! <a href='boya_listele.php' class='alert-link'>Listeyi Gör</a></div>";
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
  <title>Boya Kaydı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">Boya Kaydı Ekle</h2>

  <?php if (isset($mesaj)) echo $mesaj; ?>

  <form method="POST" class="border p-4 rounded shadow-sm bg-light">
    <div class="mb-3">
      <label for="tarih" class="form-label">Tarih</label>
      <input type="date" id="tarih" name="tarih" class="form-control" value="<?= htmlspecialchars($bugun) ?>" required>
    </div>

    <div class="mb-3">
      <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
      <select name="kumas_cinsi" id="kumas_cinsi" class="form-select" required>
        <option value="">Seçiniz</option>
        <?php
        $kumas_sonuc->data_seek(0); // Sonucu sıfırla
        while($kumas = $kumas_sonuc->fetch_assoc()): ?>
          <option value="<?= $kumas['id'] ?>"><?= htmlspecialchars($kumas['kumas_cinsi']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="miktar_mt" class="form-label">Miktar (mt)</label>
      <input type="number" step="0.01" id="miktar_mt" name="miktar_mt" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="renk" class="form-label">Renk</label>
      <input type="text" id="renk" name="renk" class="form-control" required>
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