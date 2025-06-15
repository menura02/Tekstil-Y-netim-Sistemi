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
    $stmt = $baglanti->prepare("DELETE FROM boya WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: boya_listele.php");
    exit();
}

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guncelle_id"])) {
    $id = $_POST["guncelle_id"];
    $tarih = $_POST["tarih"];
    $kumas_cinsleri_id = $_POST["kumas_cinsi"];
    $miktar_mt = $_POST["miktar_mt"];
    $renk = $_POST["renk"];
    $ucret_tl = $_POST["ucret_tl"];
    $aciklama = $_POST["aciklama"];
    $kullanici_id = $_SESSION["kullanici_id"];

    $sql = "UPDATE boya SET tarih=?, kumas_cinsleri_id=?, miktar_mt=?, renk=?, ucret_tl=?, aciklama=?, user_id=? WHERE id=?";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("sidsssii", $tarih, $kumas_cinsleri_id, $miktar_mt, $renk, $ucret_tl, $aciklama, $kullanici_id, $id);
    $stmt->execute();
    header("Location: boya_listele.php");
    exit();
}

// Düzenlenecek kayıt
$duzenlenecek = null;
if (isset($_GET["duzenle"])) {
    $id = intval($_GET["duzenle"]);
    $stmt = $baglanti->prepare("SELECT * FROM boya WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $sonuc = $stmt->get_result();
    $duzenlenecek = $sonuc->fetch_assoc();
}

// Gerekli veriler
$kumaslar = $baglanti->query("SELECT id, kumas_cinsi FROM kumas_cinsleri");
$sql = "SELECT b.*, k.kumas_cinsi, u.username 
        FROM boya b 
        LEFT JOIN kumas_cinsleri k ON b.kumas_cinsleri_id = k.id
        LEFT JOIN users u ON b.user_id = u.id
        ORDER BY b.tarih DESC";
$sonuclar = $baglanti->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Boya Kayıtları</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2 class="mb-4">Boya Kayıtları</h2>

  <?php if ($duzenlenecek) { ?>
    <div class="card mb-4">
      <div class="card-header bg-warning">Boya Kaydı Güncelle</div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="guncelle_id" value="<?= $duzenlenecek["id"] ?>">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Tarih</label>
              <input type="date" name="tarih" class="form-control" value="<?= $duzenlenecek["tarih"] ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Kumaş Cinsi</label>
              <select name="kumas_cinsi" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php while ($kumas = $kumaslar->fetch_assoc()) { ?>
                  <option value="<?= $kumas["id"] ?>" <?= $duzenlenecek["kumas_cinsleri_id"] == $kumas["id"] ? "selected" : "" ?>>
                    <?= htmlspecialchars($kumas["kumas_cinsi"]) ?>
                  </option>
                <?php } $kumaslar->data_seek(0); ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Miktar (mt)</label>
              <input type="number" step="0.01" name="miktar_mt" class="form-control" value="<?= $duzenlenecek["miktar_mt"] ?>" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Renk</label>
              <input type="text" name="renk" class="form-control" value="<?= htmlspecialchars($duzenlenecek["renk"]) ?>" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Ücret (₺)</label>
              <input type="number" step="0.01" name="ucret_tl" class="form-control" value="<?= $duzenlenecek["ucret_tl"] ?>" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Açıklama</label>
              <textarea name="aciklama" class="form-control"><?= htmlspecialchars($duzenlenecek["aciklama"]) ?></textarea>
            </div>
          </div>
          <div class="mt-3">
            <button class="btn btn-primary">Güncelle</button>
            <a href="boya_listele.php" class="btn btn-secondary ms-2">İptal</a>
          </div>
        </form>
      </div>
    </div>
  <?php } ?>

  <table class="table table-bordered table-hover table-striped">
    <thead class="table-dark">
      <tr>
        <th>Tarih</th>
        <th>Kumaş Cinsi</th>
        <th>Miktar (mt)</th>
        <th>Renk</th>
        <th>Ücret (₺)</th>
        <th>Açıklama</th>
        <th>Ekleyen</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($satir = $sonuclar->fetch_assoc()) { ?>
        <tr>
          <td><?= date("d/m/Y", strtotime($satir["tarih"])) ?></td>
          <td><?= htmlspecialchars($satir["kumas_cinsi"] ?: "Bilinmiyor") ?></td>
          <td><?= $satir["miktar_mt"] ?></td>
          <td><?= htmlspecialchars($satir["renk"]) ?></td>
          <td><?= $satir["ucret_tl"] ?></td>
          <td><?= htmlspecialchars($satir["aciklama"]) ?></td>
          <td><?= htmlspecialchars($satir["username"]) ?></td>
          <td>
            <a href="?duzenle=<?= $satir["id"] ?>" class="btn btn-sm btn-warning">Düzenle</a>
            <a href="?sil=<?= $satir["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <a href="boya_ekle.php" class="btn btn-success">Yeni Boya Kaydı</a>
  <a href="anasayfa.php" class="btn btn-secondary ms-2">Ana Sayfa</a>
</body>
</html>