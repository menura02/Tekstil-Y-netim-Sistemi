<?php
session_start();
if (!isset($_SESSION["kullanici"]) || $_SESSION["rol"] !== "admin") {
    echo "<div style=\"text-align: center; margin-top: 5rem; font-size: 1.5rem;\">❌ Bu sayfaya erişim yetkiniz yok.</div>";
    exit();
}
include "baglan.php";

// Silme işlemi
if (isset($_GET["sil"])) {
    $id = intval($_GET["sil"]);
    $sql = "DELETE FROM iplik_alim WHERE id = ?";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: iplik_listele.php");
    exit();
}

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guncelle_id"])) {
    $id = $_POST["guncelle_id"];
    $tarih = $_POST["tarih"]; // Tarih zaten YYYY-MM-DD formatında geliyor
    $iplik_turu = $_POST["iplik_turu"];
    $miktar_kg = $_POST["miktar_kg"];
    $fiyat_tl = $_POST["fiyat_tl"];
    $aciklama = $_POST["aciklama"];
    $kullanici_id = $_SESSION["kullanici_id"];

    $sql = "UPDATE iplik_alim SET tarih=?, iplik_turu=?, miktar_kg=?, fiyat_tl=?, aciklama=?, user_id=? WHERE id=?";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("ssddsii", $tarih, $iplik_turu, $miktar_kg, $fiyat_tl, $aciklama, $kullanici_id, $id);
    $stmt->execute();
    header("Location: iplik_listele.php");
    exit();
}

// Düzenlenecek kayıt
$duzenlenecek = null;
if (isset($_GET["duzenle"])) {
    $id = intval($_GET["duzenle"]);
    $stmt = $baglanti->prepare("SELECT * FROM iplik_alim WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $sonuc = $stmt->get_result();
    $duzenlenecek = $sonuc->fetch_assoc();
}

// Tüm kayıtları çek
$sql = "SELECT i.*, u.username 
        FROM iplik_alim i 
        LEFT JOIN users u ON i.user_id = u.id 
        ORDER BY i.tarih DESC";
$veriler = $baglanti->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>İplik Alımları</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">İplik Alımları</h2>

  <?php if ($duzenlenecek) { ?>
    <div class="card mb-4">
      <div class="card-header bg-warning">İplik Kaydı Güncelle</div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="guncelle_id" value="<?= $duzenlenecek["id"] ?>">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Tarih</label>
              <!-- Keep the input in YYYY-MM-DD for database compatibility -->
              <input type="date" name="tarih" class="form-control" value="<?= $duzenlenecek["tarih"] ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">İplik Türü</label>
              <input type="text" name="iplik_turu" class="form-control" value="<?= $duzenlenecek["iplik_turu"] ?>" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Miktar (kg)</label>
              <input type="number" step="0.01" name="miktar_kg" class="form-control" value="<?= $duzenlenecek["miktar_kg"] ?>" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Fiyat ($)</label>
              <input type="number" step="0.01" name="fiyat_tl" class="form-control" value="<?= $duzenlenecek["fiyat_tl"] ?>" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Açıklama</label>
              <textarea name="aciklama" class="form-control"><?= $duzenlenecek["aciklama"] ?></textarea>
            </div>
          </div>
          <div class="mt-3">
            <button class="btn btn-primary">Güncelle</button>
            <a href="iplik_listele.php" class="btn btn-secondary ms-2">İptal</a>
          </div>
        </form>
      </div>
    </div>
  <?php } ?>

  <table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>Tarih</th>
        <th>İplik Türü</th>
        <th>Miktar (kg)</th>
        <th>Fiyat ($)</th>
        <th>Açıklama</th>
        <th>Ekleyen</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($satir = $veriler->fetch_assoc()) { ?>
        <tr>
          <!-- Format the date as DD/MM/YYYY for display -->
          <td><?php 
            $date = new DateTime($satir["tarih"]);
            echo $date->format('d/m/Y');
          ?></td>
          <td><?= $satir["iplik_turu"]; ?></td>
          <td><?= $satir["miktar_kg"]; ?></td>
          <td><?= $satir["fiyat_tl"]; ?></td>
          <td><?= $satir["aciklama"]; ?></td>
          <td><?= $satir["username"]; ?></td>
          <td>
            <a href="?duzenle=<?= $satir["id"]; ?>" class="btn btn-sm btn-warning">Düzenle</a>
            <a href="?sil=<?= $satir["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')">Sil</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <a href="iplik_ekle.php" class="btn btn-success">Yeni İplik Ekle</a>
  <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>

</body>
</html>