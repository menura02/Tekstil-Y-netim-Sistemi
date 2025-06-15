<?php
session_start();
include "baglan.php";

// Sadece admin kullanıcılar erişebilsin
if (!isset($_SESSION["kullanici"]) || $_SESSION["rol"] !== "admin") {
    echo "<div style=\"text-align: center; margin-top: 5rem; font-size: 1.5rem;\">❌ Bu sayfaya erişim yetkiniz yok.</div>";
    exit();
}

// Rol güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kullanici_id"], $_POST["yeni_rol"])) {
    $kullanici_id = $_POST["kullanici_id"];
    $yeni_rol = $_POST["yeni_rol"];

    $guncelle = $baglanti->prepare("UPDATE users SET rol = ? WHERE id = ?");
    $guncelle->bind_param("si", $yeni_rol, $kullanici_id);
    $guncelle->execute();
    $guncelle->close();
}

// Kullanıcıları listele
$sonuc = $baglanti->query("SELECT id, username, rol FROM users");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Admin Paneli - Kullanıcı Yönetimi</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>Mevcut Rol</th>
                <th>Rolü Değiştir</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($kullanici = $sonuc->fetch_assoc()): ?>
            <tr>
                <td><?= $kullanici["id"] ?></td>
                <td><?= htmlspecialchars($kullanici["username"]) ?></td>
                <td><?= $kullanici["rol"] ?></td>
                <td>
                    <form method="post" class="d-flex">
                        <input type="hidden" name="kullanici_id" value="<?= $kullanici["id"] ?>">
                        <select name="yeni_rol" class="form-select me-2">
                            <option value="kullanici" <?= $kullanici["rol"] == "kullanici" ? "selected" : "" ?>>kullanici</option>
                            <option value="admin" <?= $kullanici["rol"] == "admin" ? "selected" : "" ?>>admin</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="anasayfa.php" class="btn btn-secondary">Geri Dön</a>
</body>
</html>
