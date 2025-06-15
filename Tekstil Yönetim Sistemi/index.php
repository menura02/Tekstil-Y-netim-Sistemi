<?php
session_start();
include "baglan.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $baglanti->prepare("SELECT id, username, password, rol FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $sonuc = $stmt->get_result();

    if ($sonuc->num_rows == 1) {
        $kullanici = $sonuc->fetch_assoc();
        if (password_verify($password, $kullanici["password"])) {
            $_SESSION["kullanici"] = $kullanici["username"];
            $_SESSION["kullanici_id"] = $kullanici["id"];
            $_SESSION["rol"] = $kullanici["rol"];
            header("Location: anasayfa.php");
            exit();
        } else {
            $mesaj = "<div class='alert alert-danger'>❌ Hatalı şifre.</div>";
        }
    } else {
        $mesaj = "<div class='alert alert-warning'>⚠️ Kullanıcı bulunamadı.</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">X Tekstil - Giriş Yap</h2>

  <?php if (isset($mesaj)) echo $mesaj; ?>

  <form method="POST" class="border p-4 rounded bg-light shadow-sm" style="max-width: 400px;">
    <div class="mb-3">
      <label class="form-label">Kullanıcı Adı</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Şifre</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
  </form>

  <p class="mt-3">Hesabınız yok mu? <a href="kayit.php">Kayıt olun</a></p>

</body>
</html>
