# 🛡️ PHP CRUD Kullanıcı Yönetim Paneli

> PHP öğrenme sürecimde PDO kullanarak sıfırdan yazdığım, oturum yönetimli, güvenli bir kullanıcı CRUD uygulaması.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PDO](https://img.shields.io/badge/PDO-Prepared_Statements-green?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Tamamlandı-brightgreen?style=for-the-badge)

---

## 📌 Proje Hakkında

PHP ile çalışırken özellikle PDO, güvenli sorgu yönetimi ve oturum yapısı konularını daha sağlam oturtmak istedim. Bu proje bunun için açtığım bir alan — temiz bir CRUD uygulaması üzerinden bu konuları gerçek kodla pekiştiriyorum.

Arayüz tasarımı sade tutulmuş olsa da **tüm PHP ve SQL kodları tarafımdan sıfırdan yazılmıştır.** Her satırın ne işe yaradığını anlayarak ilerlemeye özen gösterdim.

---

## ✨ Özellikler

- 🔐 **Güvenli Login Sistemi** — `password_hash()` ve `password_verify()` ile şifrelenmiş giriş
- 📋 **Kullanıcı Listeleme** — Tüm kayıtlı kullanıcıları tablo halinde görüntüleme
- ➕ **Kullanıcı Ekleme** — Form doğrulamalı yeni kullanıcı kaydı
- ✏️ **Kullanıcı Güncelleme** — Mevcut kaydı düzenleme
- 🗑️ **Kullanıcı Silme** — Onay mekanizmalı silme işlemi
- 🚪 **Oturum Yönetimi** — `$_SESSION` ile sayfa bazlı yetkilendirme
- 🛡️ **SQL Injection Koruması** — PDO Prepared Statements
- 🔒 **XSS Koruması** — `htmlspecialchars()` ile güvenli çıktı

---

## 🗂️ Proje Yapısı

```
site/
├── admin/           → Yöneticilerle ilgili dosyalar
|    ├── guncelle.php      → Yönetici Bilgilerini Güncelleme
|    ├── listele.php       → Yöneticileri Listeleme
|    └── sil.php           → Yöneticileri Silme
├── db.php           → Veritabanı bağlantısı (PDO)
├── auth.php         → Merkezi oturum/yetki kontrolü (DRY prensibi)
├── login.php        → Giriş formu ve oturum başlatma
├── logout.php       → Oturumu kapatma
├── index.php        → Ana sayfa
├── listele.php      → Kullanıcıları listeleme
├── kaydet.php       → Yeni kullanıcı ekleme
├── guncelle.php     → Kullanıcı güncelleme
└── sil.php          → Kullanıcı silme
```

---

## 🛠️ Kullanılan Teknolojiler

| Teknoloji | Kullanım Amacı |
|-----------|---------------|
| **PHP 8.x** | Sunucu taraflı programlama dili |
| **MySQL** | Veritabanı yönetim sistemi |
| **PDO** | Güvenli veritabanı bağlantısı ve sorgu yönetimi |
| **HTML/CSS** | Arayüz yapısı ve stil |
| **PHP Sessions** | Kullanıcı oturum yönetimi |

---

## ⚙️ Kurulum

### Gereksinimler
- XAMPP / WAMP / LAMP (PHP 8.x + MySQL)
- phpMyAdmin veya MySQL CLI

### Adımlar

**1. Dosyaları kopyala**
```bash
# Proje dosyalarını htdocs altına koy
# Windows: C:/xampp/htdocs/site/
# Linux:   /opt/lampp/htdocs/site/
```

**2. Veritabanını oluştur**

phpMyAdmin'i aç ve aşağıdaki SQL sorgularını çalıştır:

```sql
-- Veritabanı oluştur
CREATE DATABASE site_db CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
USE site_db;

-- Yönetici tablosu
CREATE TABLE yoneticiler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL
);

-- Varsayılan admin kullanıcısını ekle (şifre: admin123)
INSERT INTO yoneticiler (email, sifre)
VALUES ('admin', '$2a$12$ri2dnfVkqe52E9W4BH/VtOoVI42IO0VCJE7qKRDMobXVAcv9J2tM2');

-- Kullanıcı tablosu
CREATE TABLE kullanicilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(100) NOT NULL,
    soyad VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefon VARCHAR(20),
    olusturma_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**3. Veritabanı bağlantısını ayarla**

`db.php` dosyasını kendi ortamına göre düzenle:

```php
$host      = 'localhost';
$dbadi     = 'site_db';
$kullanici = 'root';
$sifre     = '';        // XAMPP'ta genelde boş
```

**4. Projeyi çalıştır**

Tarayıcıda aç:
```
http://localhost/site/login.php
```

**Varsayılan giriş bilgileri:**
```
Kullanıcı Adı : admin
Şifre         : admin123
```

> ⚠️ Canlı ortama taşımadan önce şifrenizi değiştirmeyi unutmayın!

---

## 🔐 Güvenlik Yaklaşımları

Bu projede PHP öğrenirken güvenliği de öğrenmeye özen gösterdim:

### SQL Injection Koruması
```php
// ❌ Tehlikeli yöntem (kullanılmadı)
$query = "SELECT * FROM kullanicilar WHERE email = '$email'";

// ✅ Güvenli yöntem — PDO Prepared Statement
$stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE email = :email");
$stmt->execute([':email' => $email]);
```

### Şifre Güvenliği
```php
// Şifre kayıt sırasında hash'lenir
$hash = password_hash($sifre, PASSWORD_DEFAULT);

// Giriş sırasında güvenli karşılaştırma
if (password_verify($girilenSifre, $hashSifre)) { ... }
```

### XSS Koruması
```php
// Kullanıcıdan gelen veriler ekrana yazdırılmadan önce temizlenir
echo htmlspecialchars($kullanici['ad']);
```

### Merkezi Oturum Yönetimi — `auth.php`

Oturum kontrolünü her sayfaya tekrar tekrar yazmak yerine **DRY (Don't Repeat Yourself)** prensibini uygulayarak tek bir dosyada topladım. Korunan her sayfa sadece bu dosyayı çağırır.

```php
// auth.php
session_start();
if (!isset($_SESSION['giris_yapildi'])) {
    header('Location: login.php');
    exit;
}
```

```php
// listele.php, kaydet.php, guncelle.php, sil.php, index.php
require 'auth.php'; // tek satır yeterli — kontrol merkezi burada
```

Bu yaklaşım sayesinde oturum mantığını değiştirmek gerektiğinde sadece `auth.php` dosyasını düzenlemek yeterli olur.

---

## 📚 Bu Projede Öğrendiklerim

Bu projeyi tamamlayarak şu konularda pratik yaptım:

- **PDO ile veritabanı bağlantısı** kurmak ve yönetmek
- **Prepared Statements** ile güvenli SQL sorguları yazmak
- **PHP Oturumları** (`$_SESSION`) ile sayfa bazlı yetkilendirme yapmak
- **DRY prensibi** — tekrar eden kodu `auth.php` ile merkezi hale getirmek
- **GET ve POST** metodlarının farkını pratikte anlamak
- **`password_hash()` / `password_verify()`** ile güvenli şifre yönetimi
- **Form doğrulama** ve kullanıcı girdilerini temizleme
- **`htmlspecialchars()`** ile XSS saldırılarına karşı önlem almak
- PHP'de **`header('Location: ...')`** ile yönlendirme yapmak
- Veritabanı hatalarını **try/catch** ile yakalamak

---

## 🗺️ Gelecek Geliştirmeler

- [ ] Arama ve filtreleme özelliği
- [ ] Sayfalama (pagination)
- [ ] Kullanıcı rolleri (admin / editör)
- [ ] AJAX ile sayfa yenilemeden işlem yapma
- [ ] Responsive tasarım iyileştirmeleri

---

## 👨‍💻 Geliştirici Notu

> PHP'yi temel düzeyde biliyorum; bu proje PDO, güvenli sorgu yönetimi  
> ve oturum yapısı gibi konuları daha sağlam oturtmak için açtığım bir alan
> Arayüz kasıtlı olarak sade tutuldu, odak tamamen backend tarafında.  
> Proje henüz bitmedi — geliştirmeye devam ediyorum.

---

## 📄 Lisans

Bu proje [MIT Lisansı](LICENSE) ile lisanslanmıştır. Öğrenme amaçlı serbestçe kullanılabilir.

---

<p align="center">
  PHP öğrenme yolculuğumun ilk adımı 🚀
</p>