<?php
include "baglan.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Kullanıcı adı daha önce alınmış mı kontrol et
    $kontrol = $baglanti->prepare("SELECT id FROM users WHERE username = ?");
    $kontrol->bind_param("s", $username);
    $kontrol->execute();
    $kontrol->store_result();

    if ($kontrol->num_rows > 0) {
        $mesaj = "<div class='alert alert-warning'>⚠️ Bu kullanıcı adı zaten alınmış.</div>";
    } else {
        $rol = 'kullanici'; // varsayılan rol

        $sql = "INSERT INTO users (username, password, rol) VALUES (?, ?, ?)";
        $stmt = $baglanti->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $rol);
        if ($stmt->execute()) {
            $mesaj = "<div class='alert alert-success'>✅ Kayıt başarılı! <a href='index.php' class='alert-link'>Giriş yap</a></div>";
        } else {
            $mesaj = "<div class='alert alert-danger'>❌ Hata: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
    $kontrol->close();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kayıt Ol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

  <h2 class="mb-4">X Tekstil - Kayıt Ol</h2>

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

    <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
  </form>

  <p class="mt-3">Zaten hesabınız var mı? <a href="index.php">Giriş yap</a></p>

</body>
</html>
