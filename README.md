# 🚢 Navistra

Laravel tabanlı gemi-yük eşleştirme sistemi.

---

## 🛠️ Kurulum Talimatları (Lokal)

### 📦 Gereksinimler:

- PHP >= 8.1  
- Composer  
- Node.js + npm  
- MySQL (veya MariaDB)  
- Laravel destekli bir web sunucusu (örn. Apache, Nginx)

---

### 🔧 Adımlar:

1. **Projeyi klonla:**
   ```bash
   git clone https://github.com/alppozcann/navistra.git
   cd navistra
   ```

2. **Composer bağımlılıklarını yükle:**
   ```bash
   composer install
   ```

3. **.env dosyasını oluştur ve düzenle:**
   ```bash
   cp .env.example .env
   ```
   `.env` dosyasını aç ve aşağıdaki bilgileri doldur:
   ```env
   DB_DATABASE=navistra_db
   DB_USERNAME=root
   DB_PASSWORD=senin_sifren
   ```

4. **Uygulama anahtarını oluştur:**
   ```bash
   php artisan key:generate
   ```

5. **Veritabanını oluştur ve migrate et:**
   MySQL/MariaDB üzerinde:
   ```sql
   CREATE DATABASE navistra_db;
   ```

   Terminalde:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Node bağımlılıklarını yükle ve derle:**
   ```bash
   npm install
   npm run dev
   ```

7. **Projeyi başlat:**
   ```bash
   php artisan serve
   ```
   [http://127.0.0.1:8000](http://127.0.0.1:8000) adresinden ulaşabilirsin.

---

## 🌐 Kurulum Yapmadan Test Etmek İsteyenler İçin

### 🔁 Uygulamayı Çevrimiçi Yayına Alma Yöntemleri:

1. **[Render](https://render.com)**  
   - Laravel destekli, GitHub bağlantısıyla hızlı deploy.  
   - `.env` ayarlarını web panelden yapabilirsin.  
   - Ücretsiz planı mevcuttur.

2. **[Railway](https://railway.app)**  
   - Modern arayüz, hızlı kurulum.  
   - Veritabanı servislerini kolayca bağlayabilirsin.  
   - Projeyi birkaç tıklamayla yayına alırsın.

3. **[Ngrok](https://ngrok.com)** *(lokal sunucuyu paylaşmak için)*  
   - Bilgisayarında çalışan Laravel uygulamasını dış dünyaya açar:  
     ```bash
     php artisan serve
     ngrok http 8000
     ```
   - Ngrok sana geçici bir bağlantı verir, bunu arkadaşlarınla paylaşabilirsin.

---

## 👥 Giriş Bilgileri

Varsayılan kullanıcı yoksa `Register` kısmından kullanıcı oluşturabilirsin.

---

**Geliştirici:** [Alp Özcan](https://github.com/alppozcann)
