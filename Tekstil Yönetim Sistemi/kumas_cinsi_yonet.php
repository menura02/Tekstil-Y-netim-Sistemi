<?php
session_start();
if (!isset($_SESSION["kullanici"]) || $_SESSION["rol"] !== "admin") {
    echo "<div style=\"text-align: center; margin-top: 5rem; font-size: 1.5rem;\">❌ Bu sayfaya erişim yetkiniz yok.</div>";
    exit();
}

include "baglan.php";

$kullanici_id = $_SESSION["kullanici_id"];
$mesaj = "";

// Kumaş cinsi ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kumas_cinsi_ekle"])) {
    $kumas_cinsi = trim($_POST["kumas_cinsi"]);
    if (!empty($kumas_cinsi)) {
        $stmt = $baglanti->prepare("INSERT INTO kumas_cinsleri (kumas_cinsi) VALUES (?)");
        $stmt->bind_param("s", $kumas_cinsi);
        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>Kumaş cinsi başarıyla eklendi!</div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $mesaj = "<div class='alert alert-danger'>Kumaş cinsi boş olamaz!</div>";
    }
}

// Kumaş cinsi silme
if (isset($_GET["sil_kumas_cinsi"])) {
    $id = intval($_GET["sil_kumas_cinsi"]);
    // Kumaş cinsinin kullanımda olup olmadığını kontrol et
    $tables = ["cig_kumas", "alpaka_kumas", "zefir_kumas", "poliviskon_kumas", "kristal_saten_kumas"];
    $kullanilan = 0;
    foreach ($tables as $table) {
        if ($baglanti->query("SHOW TABLES LIKE '$table'")->num_rows > 0) {
            $stmt = $baglanti->prepare("SELECT COUNT(*) as kullanilan FROM $table WHERE kumas_cinsi = (SELECT kumas_cinsi FROM kumas_cinsleri WHERE id = ?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $sonuc = $stmt->get_result()->fetch_assoc();
            $kullanilan += $sonuc["kullanilan"];
            $stmt->close();
        }
    }
    if ($kullanilan > 0) {
        $mesaj = "<div class='alert alert-danger'>Bu kumaş cinsi stokta kullanıldığı için silinemez!</div>";
    } else {
        $stmt = $baglanti->prepare("DELETE FROM kumas_cinsleri WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>Kumaş cinsi başarıyla silindi!</div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}

// Kumaş cinslerini çek
$kumas_cinsleri = $baglanti->query("SELECT * FROM kumas_cinsleri ORDER BY kumas_cinsi");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kumaş Cinsi Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Kumaş Cinsi Yönetimi</h2>

    <?php if ($mesaj) echo $mesaj; ?>

    <!-- Kumaş Cinsi Ekleme -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Kumaş Cinsi Ekle</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="kumas_cinsi" class="form-label">Kumaş Cinsi</label>
                    <input type="text" id="kumas_cinsi" name="kumas_cinsi" class="form-control" required>
                </div>
                <button type="submit" name="kumas_cinsi_ekle" class="btn btn-primary">Ekle</button>
            </form>
        </div>
    </div>

    <!-- Kumaş Cinsleri Listesi -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Kumaş Cinsleri</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Kumaş Cinsi</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($satir = $kumas_cinsleri->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($satir["kumas_cinsi"]) ?></td>
                            <td>
                                <a href="?sil_kumas_cinsi=<?= $satir["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kumaş cinsini silmek istediğinize emin misiniz?')">Sil</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="anasayfa.php" class="btn btn-secondary">Ana Sayfa</a>
</body>
</html>