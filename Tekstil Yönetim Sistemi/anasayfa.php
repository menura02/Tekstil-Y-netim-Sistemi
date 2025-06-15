<?php
session_start();
if (!isset($_SESSION["kullanici"])) {
    header("Location: giris.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kontrol Paneli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>X Tekstil Kontrol Paneli</h2>
    <a href="cikis.php" class="btn btn-danger">Çıkış Yap</a>
  </div>

  <div class="alert alert-info">
    Hoş geldiniz, <strong><?php echo $_SESSION["kullanici"]; ?></strong>!
  </div>

  <div class="row g-4">
    <!-- İplik -->
    <div class="col-md-4">
      <div class="card shadow-sm border-primary">
        <div class="card-body">
          <h5 class="card-title text-primary">İplik İşlemleri</h5>
          <a href="iplik_ekle.php" class="btn btn-outline-primary btn-sm mb-2 w-100">İplik Alımı Ekle</a>
          <a href="iplik_listele.php" class="btn btn-outline-primary btn-sm w-100">İplik Alımlarını Gör</a>
        </div>
      </div>
    </div>

    <!-- Dokuma -->
    <div class="col-md-4">
      <div class="card shadow-sm border-success">
        <div class="card-body">
          <h5 class="card-title text-success">Dokuma İşlemleri</h5>
          <a href="dokuma_ekle.php" class="btn btn-outline-success btn-sm mb-2 w-100">Dokuma Kaydı Ekle</a>
          <a href="dokuma_listele.php" class="btn btn-outline-success btn-sm w-100">Dokuma Kayıtlarını Gör</a>
        </div>
      </div>
    </div>

    <!-- Boya -->
    <div class="col-md-4">
      <div class="card shadow-sm border-warning">
        <div class="card-body">
          <h5 class="card-title text-warning">Boya İşlemleri</h5>
          <a href="boya_ekle.php" class="btn btn-outline-warning btn-sm mb-2 w-100">Boya Kaydı Ekle</a>
          <a href="boya_listele.php" class="btn btn-outline-warning btn-sm w-100">Boya Kayıtlarını Gör</a>
        </div>
      </div>
    </div>

    <!-- Stok -->
    <div class="col-md-4">
      <div class="card shadow-sm border-info">
        <div class="card-body">
          <h5 class="card-title text-info">Stok İşlemleri</h5>
          <a href="stok_ekle.php" class="btn btn-outline-info btn-sm mb-2 w-100">Stok Girişi</a>
          <a href="stok_listele.php" class="btn btn-outline-info btn-sm w-100">Stok Listesi</a>
        </div>
      </div>
    </div>

    <!-- Satış -->
    <div class="col-md-4">
      <div class="card shadow-sm border-danger">
        <div class="card-body">
          <h5 class="card-title text-danger">Satış İşlemleri</h5>
          <a href="satis_ekle.php" class="btn btn-outline-danger btn-sm mb-2 w-100">Satış Ekle</a>
          <a href="satis_listele.php" class="btn btn-outline-danger btn-sm w-100">Satışları Gör</a>
        </div>
      </div>
    </div>

    <!-- Kumaş Cinslerini Yönet - Kullanıcıları Yönet -->
    <div class="col-md-4">
      <div class="">
          <a href="kumas_cinsi_yonet.php" class="btn btn-primary container">Kumaş Cinslerini Yönet</a>
          <a href="admin_panel.php" class="btn btn-primary container mt-3">Kullanıcıları Yönet</a>
      </div>
    </div>
  </div>
  
</body>
</html>
