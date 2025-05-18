# Grup 35 Bijuteri Projesi

Bu proje, PHP ve MySQL kullanılarak geliştirilmiş basit bir bijuteri mağazası otomasyon sistemidir.

## 📁 Klasör Yapısı
 Admin/  
    ├─ admin.panel.php      → Yönetici ana paneli 
    ├─ admin.giris.php    	→ Yönetici girişi 
    ├─ admin.kayit.php      → Yönetici kaydı 
    ├─ admin.cikis.php     	→ Yönetici çıkışı  
    └─ admin.duzenle/       → Yönetici düzen işlemleri 
        ├─ stok.php         → Stok bilgilerini yönetir 
        ├─ urun.ekle.php    → Yeni ürün ekler 
        └─ urun.sil.php     → Ürün silme işlemi 
        
 favori/  
    ├─ favorilerim.php      → Favori ürünler listesi 
    ├─ favorilere.ekle.php  → Favoriye ürün ekleme 
    └─ favori.sil.php       → Favoriden silme 
    
 sepet/  
    ├─ sepet.php            → Sepet görüntüleme 
    └─ sepete.ekle.php      → Sepete ürün ekleme 
    
 urunler/  
    ├─ bileklik.php         → Bileklik ürünleri 
    ├─ filtre.php           → Ürün filtreleme 
    ├─ kolye.php            → Kolye ürünleri 
    ├─ kupe.php             → Küpe ürünleri 
    ├─ yüzük.php            → Yüzük ürünleri 
    ├─ ürün.detay.php       → Ürün detayları 
    └─ images/              → Ürün görselleri klasörü 
    
📄 Diğer Dosyalar  
    ├─ anasayfa.php         → Ana sayfa 
    ├─ baglanti.php         → Veritabanı bağlantısı 
    ├─ giris.php            → Kullanıcı girişi 
    ├─ kayit.php            → Kullanıcı kaydı 
    ├─ cikis.php            → Kullanıcı çıkışı 
    ├─ profil.php           → Kullanıcı profili 
    ├─ siparislerim.php     → Sipariş geçmişi 
    ├─ siparis.durum.php    → Sipariş durumu 
    ├─ urun.arama.php		    → Ürün arama

## ⚙️ Teknolojiler

- PHP 7.x
- MySQL
- HTML / CSS
- XAMPP

## 🧪 Kurulum

1. htdocs içine projeyi kopyalayın.
2. XAMPP üzerinden Apache ve MySQL’i başlatın.
3. veritabani.sql dosyasını phpMyAdmin üzerinden içe aktarın.
4. http://localhost/bijuteri/giris.php adresinden projeyi başlatın.

## 👥 Geliştirici

- Grup 35 - Veri Tabanı Dersi

## 📄 Lisans

Bu proje eğitim amaçlı hazırlanmıştır. Herhangi bir ticari kullanım için uygun değildir.
