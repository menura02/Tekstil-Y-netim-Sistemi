# X Tekstil Yönetim Sistemi

X Tekstil Yönetim Sistemi, bir tekstil şirketinin üretim süreçlerini ve stoklarını yönetmek için geliştirilmiş bir web uygulamasıdır. Uygulama, iplik alımı, dokuma, boya, stok ve satış işlemlerinin kaydedilmesini ve takip edilmesini sağlar. Kullanıcı dostu bir arayüzle, farklı kullanıcı rolleri (admin ve kullanıcı) için yetkilendirme sunar.

## Özellikler
- **Kullanıcı Yönetimi**:
  - Kullanıcı kayıt ve giriş işlemleri.
  - Admin paneli üzerinden kullanıcı rolleri (admin/kullanıcı) yönetimi.
- **İplik Yönetimi**:
  - İplik alımı ve stok girişi kaydetme.
  - İplik türleri, miktarları ve fiyatlarının listelenmesi (admin yetkisiyle düzenleme/silme).
- **Dokuma Yönetimi**:
  - Atkı ve çözgü iplikleriyle dokuma kayıtları oluşturma.
  - Dokuma işlemlerinin listelenmesi, düzenlenmesi ve silinmesi (admin yetkisiyle).
- **Boya Yönetimi**:
  - Kumaş boyama işlemlerinin kaydedilmesi (kumaş cinsi, renk, miktar, ücret).
  - Boya kayıtlarının listelenmesi ve yönetimi (admin yetkisiyle).
- **Stok Yönetimi**:
  - Çeşitli malzeme türleri için stok girişi (iplik, çiğ kumaş, alpaka kumaş, zefir kumaş, saten kumaş).
  - Stokların detaylı listelenmesi, düzenlenmesi ve silinmesi (admin yetkisiyle).
- **Satış Yönetimi**:
  - Kumaş satışlarının kaydedilmesi (kumaş cinsi, renk, miktar, fiyat).
  - Satış kayıtlarının listelenmesi ve yönetimi (admin yetkisiyle).
- **Kumaş Cinsi Yönetimi**:
  - Kumaş türlerinin eklenmesi ve silinmesi (admin yetkisiyle).
  - Kumaş cinslerinin diğer modüllerde (boya, dokuma, stok, satış) kullanılması.
 
## Ekran Görüntüleri
![Login](https://github.com/user-attachments/assets/4380e3d6-e310-4b77-a834-21a773e4cc53)

![Main Page](https://github.com/user-attachments/assets/55bdfd7c-861d-4a83-8426-3cb62bbaf186)

![dokuma_ekle](https://github.com/user-attachments/assets/c7a420ef-a164-4903-95bc-419ef10ae0aa)

## Tekstil Yönetim Sistemi Proje Tanıtım Videosu

Bu projeyle ilgili hazırladığım tanıtım videosunu buradan izleyebilirsiniz:

👉 [YouTube'da İzle](https://www.youtube.com/watch?v=G5wmaOQod7w)

[![X Tekstil Tanıtım Videosu](https://img.youtube.com/vi/G5wmaOQod7w/0.jpg)](https://www.youtube.com/watch?v=G5wmaOQod7w)


## Teknolojiler
- **Backend**: PHP 8.0+
- **Frontend**: HTML5, Bootstrap 5.3
- **Veritabanı**: MySQL (MariaDB 10.4+)
- **Diğer**: JavaScript (onay istemleri için)

## Gereksinimler
- **Sunucu**: Apache veya Nginx
- **PHP**: 8.0 veya üzeri
- **MySQL/MariaDB**: 10.4 veya üzeri
- **Web Tarayıcı**: Modern tarayıcılar (Chrome, Firefox, Edge)
- **Bağımlılıklar**: 
  - PHP `mysqli` uzantısı
  - Bootstrap 5.3 (CDN üzerinden sağlanıyor)

## Ekran Görüntüleri
![boya_ekle](https://github.com/user-attachments/assets/3956f89c-11ba-4656-aace-6b3dc4a262fa)
![Admin Panel](https://github.com/user-attachments/assets/4648d698-5d45-430f-a59d-bbe9731be7c2)



## Kurulum

### 1. Depoyu Klonlayın
```bash
git clone https://github.com/[kullanici-adi]/[repo-adi].git
cd [repo-adi]
```

### 2. Veritabanını Yapılandırın
- MySQL/MariaDB üzerinde yeni bir veritabanı oluşturun:
  ```sql
  CREATE DATABASE x_tekstil CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
  ```
- `x_tekstil.sql` dosyasını içe aktarın:
  ```bash
  mysql -u [kullanici] -p x_tekstil < x_tekstil.sql
  ```
- `baglan.php` dosyasındaki veritabanı bağlantı ayarlarını güncelleyin:
  ```php
  $host = "localhost";
  $kullanici = "[mysql-kullanici-adi]";
  $sifre = "[mysql-sifre]";
  $veritabani = "x_tekstil";
  ```

### 3. Dosyaları Sunucuya Kopyalayın
- Proje dosyalarını web sunucunuzun kök dizinine (ör. `/var/www/html`) kopyalayın.
- Dosya izinlerini kontrol edin (ör. `chmod -R 755 .`).

### 4. Uygulamayı Çalıştırın
- Web tarayıcınızda `http://localhost/[proje-dizini]/index.php` adresine gidin.
- Varsayılan admin hesabı ile giriş yapabilirsiniz:
  - Kullanıcı Adı: `admin123`
  - Şifre: `admin123`

## Kullanım
- **Giriş Yapma**: `index.php` üzerinden kullanıcı adı ve şifre ile giriş yapın.
- **Kontrol Paneli**: Giriş yaptıktan sonra `anasayfa.php` üzerinden modüllere (iplik, dokuma, boya, stok, satış) erişebilirsiniz.
- **Admin Yetkileri**:
  - Kayıtları listeleme, düzenleme ve silme işlemleri sadece `admin` rolüne sahip kullanıcılar tarafından yapılabilir.
  - `admin_panel.php` üzerinden kullanıcı rolleri yönetilebilir.
  - `kumas_cinsi_yonet.php` üzerinden kumaş cinsleri eklenebilir/silinebilir.
- **Kayıt Ekleme**: Her modül için ilgili `_ekle.php` sayfalarında formları doldurarak yeni kayıtlar ekleyin.
- **Kayıt Listeleme**: `_listele.php` sayfalarında kayıtları görüntüleyin, düzenleyin veya silin.

## Veritabanı Yapısı
Veritabanı (`x_tekstil`), aşağıdaki ana tabloları içerir:
- `users`: Kullanıcı bilgileri (kullanıcı adı, şifre, rol).
- `kumas_cinsleri`: Kumaş türleri (ör. Polyester Alpaka, Zefir).
- `iplik_alim`: İplik alım kayıtları (tür, miktar, fiyat).
- `iplik`: İplik stok bilgileri (tür, renk, kilo).
- `dokuma`: Dokuma işlemleri (atkı/çözgü iplik, kumaş cinsi, miktar).
- `boya`: Boya işlemleri (kumaş cinsi, renk, miktar, ücret).
- `satis`: Satış kayıtları (kumaş cinsi, renk, miktar, fiyat).
- `cig_kumas`, `alpaka_kumas`, `zefir_kumas`, `kristal_saten_kumas`: Farklı kumaş türleri için stok bilgileri.

Detaylı tablo yapısı için `x_tekstil.sql` dosyasını inceleyin.

## Güvenlik Notları
- **Şifreleme**: Kullanıcı şifreleri `password_hash` ile şifrelenmiştir.
- **SQL Injection**: Hazırlanmış ifadeler (`prepare`) kullanılarak SQL injection saldırılarına karşı koruma sağlanmıştır.
- **Yetkilendirme**: Admin işlemleri yalnızca `admin` rolüne sahip kullanıcılar için erişilebilirdir.

