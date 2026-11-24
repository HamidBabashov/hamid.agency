# Hamid Agency — hamid.agency

Veb dizayn, mobil tətbiq hazırlanması, hosting və domen satışı üçün iki dilli (AZ/EN) agentlik saytı.

**Texnologiyalar:** Laravel 11 (PHP 8.2+), MySQL, Blade + Tailwind CSS, Alpine.js, Vite

---

## Mündəricat

1. [İmkanlar](#1-imkanlar)
2. [Layihə strukturu](#2-layihə-strukturu)
3. [Lokal quraşdırma](#3-lokal-quraşdırma)
4. [.env parametrləri](#4-env-parametrləri)
5. [DirectAdmin serverinin hazırlanması](#5-directadmin-serverinin-hazırlanması)
6. [public_html → Laravel /public yönləndirməsi](#6-public_html--laravel-public-yönləndirməsi)
7. [GitHub Actions ilə avtomatik deploy](#7-github-actions-ilə-avtomatik-deploy)
8. [İlk deploy — addım-addım](#8-ilk-deploy--addım-addım)
9. [Admin panel](#9-admin-panel)
10. [Domen provayderinin qoşulması](#10-domen-provayderinin-qoşulması)
11. [Tərcümələr və yeni mətnlər](#11-tərcümələr-və-yeni-mətnlər)
12. [Testlər](#12-testlər)
13. [Problemlərin həlli](#13-problemlərin-həlli)

---

## 1. İmkanlar

| Bölmə | Təsvir |
|---|---|
| **Dillər** | `/az/...` (əsas) və `/en/...`; kök domen `/az`-a (və ya əvvəl seçilmiş dilə) yönləndirir. Seçim cookie-də yadda qalır. URL seqmentləri də tərcümə olunub (`/az/elaqe` ↔ `/en/contact`). |
| **Səhifələr** | Ana səhifə, Xidmətlər (4 ayrıca səhifə), Hosting paketləri (aylıq/illik keçid), Domenlər (axtarış + qiymət cədvəli), Portfolio (kateqoriya filtri), Haqqımızda, Əlaqə, Qiymət təklifi al, Məxfilik siyasəti, İstifadə şərtləri |
| **Satış** | Sifarişlər bazaya yazılır, admininə email gedir, statusu admin paneldə idarə olunur (yeni / əlaqə saxlanıldı / tamamlandı). Ödəniş yoxdur. |
| **Domen yoxlaması** | `DomainRegistrar` interfeysi. Hazır adapterlər: WHOIS (pulsuz), ResellerClub, Namecheap, WHMCS. |
| **Admin panel** | `/admin`. Xidmətlər, hosting paketləri, domen qiymətləri, portfolio, rəylər, sifarişlər, mesajlar. AZ/EN sahələri yan-yana. Şəkillər avtomatik WebP-ə çevrilir və kiçildilir. |
| **SEO** | `sitemap.xml` (hər iki dil, hreflang ilə), `robots.txt`, canonical, Open Graph, Schema.org (Organization, Service, FAQPage), hər dil üçün ayrıca meta title/description |
| **Təhlükəsizlik** | CSRF, formalarda rate limit (5/dəq, 30/gün), honeypot + vaxt yoxlaması, istəyə bağlı reCAPTCHA v3, admin girişində rate limit |
| **Dizayn** | Mobile-first, tünd göy (#0B1F3A) + kəhrəba vurğu rəngi, qaranlıq rejim, lazy-load şəkillər, minimal JS (Alpine.js ~20 KB gzip) |

> **Rəylər bölməsi** yalnız admin paneldən real rəy əlavə edildikdə görünür. Saytda heç bir saxta rəy yoxdur.
> **Portfolio** də boşdur — real layihələrinizi admin paneldən əlavə edin.

---

## 2. Layihə strukturu

```
app/
├── Contracts/DomainRegistrar.php            # domen provayderi interfeysi
├── Services/
│   ├── Domain/DomainAvailabilityService.php # axtarış məntiqi, keş, qiymətlər
│   ├── Domain/Adapters/                     # Whois, ResellerClub, Namecheap, Whmcs
│   └── ImageService.php                     # WebP çevirmə + ölçü kiçiltmə
├── Http/Controllers/Site/                   # ictimai səhifələr
├── Http/Controllers/Admin/                  # admin panel
├── Http/Middleware/SetLocale.php            # dil + cookie
├── Http/Middleware/ProtectAgainstSpam.php   # honeypot, vaxt, reCAPTCHA
├── Http/Requests/QuoteRequest.php           # xidmətə görə dəyişən validasiya
├── Models/Concerns/HasTranslations.php      # title_az / title_en → $model->t('title')
└── helpers.php                              # lroute(), money(), upload_url()
config/agency.php                            # əlaqə, valyuta, reCAPTCHA, admin
config/domain.php                            # domen provayderi seçimi və API açarları (.env-dən)
lang/az, lang/en                             # BÜTÜN mətnlər
resources/views/                             # Blade (layouts, partials, components, pages, admin)
.github/workflows/deploy.yml                 # avtomatik deploy
```

---

## 3. Lokal quraşdırma

Tələblər: PHP 8.2+ (`gd`, `intl`, `mbstring`, `pdo_mysql`/`pdo_sqlite`), Composer 2, Node.js 20+.

```bash
git clone https://github.com/HamidBabashov/hamid.agency.git
cd hamid.agency

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Lokal üçün `.env`-də ən azı bunları dəyişin:

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite          # və ya mysql + DB_* məlumatları
MAIL_MAILER=log               # emaillər storage/logs-a yazılır
SESSION_SECURE_COOKIE=false

ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=en-azi-10-simvol
```

```bash
touch database/database.sqlite      # yalnız sqlite üçün
php artisan migrate --seed          # cədvəllər + admin + başlanğıc məlumatlar
npm run dev                         # və ya: npm run build
php artisan serve                   # http://127.0.0.1:8000  →  /az
```

Admin panel: http://127.0.0.1:8000/admin

---

## 4. .env parametrləri

Bütün gizli məlumatlar **yalnız `.env`-də** saxlanılır. `.env` GitHub-a göndərilmir (`.gitignore`).

| Dəyişən | Təsvir |
|---|---|
| `APP_URL` | `https://hamid.agency` (sitemap, OG və emaillərdəki linklər üçün vacibdir) |
| `DB_*` | MySQL məlumatları |
| `MAIL_*` | SMTP (DirectAdmin email hesabı) |
| `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Admin hesabı (seeder yaradır; şifrə ən azı 10 simvol) |
| `AGENCY_EMAIL`, `AGENCY_PHONE`, `AGENCY_ADDRESS` | Saytda göstərilən əlaqə məlumatları (boşdursa göstərilmir) |
| `AGENCY_WHATSAPP` | Yalnız rəqəmlər: `994501234567`. Doldurulduqda WhatsApp düyməsi görünür |
| `NOTIFY_EMAIL` | Sifariş/mesaj bildirişlərinin gedəcəyi ünvan |
| `AGENCY_CURRENCY`, `AGENCY_CURRENCY_SYMBOL` | `AZN`, `₼` |
| `SOCIAL_*` | Sosial şəbəkə linkləri (boşdursa göstərilmir) |
| `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` | Google reCAPTCHA **v3** (istəyə bağlı; boşdursa yalnız honeypot işləyir) |
| `DOMAIN_PROVIDER` | `whois` \| `resellerclub` \| `namecheap` \| `whmcs` |
| `RESELLERCLUB_*`, `NAMECHEAP_*`, `WHMCS_*` | Provayder API açarları |
| `DEPLOY_TOKEN` | Yalnız FTP deploy üçün (ən azı 32 simvol). Boşdursa post-deploy endpoint bağlıdır |

> `.env` dəyişdikdən sonra serverdə keşi yeniləyin: `php artisan config:cache` (və ya növbəti deploy bunu edəcək).

---

## 5. DirectAdmin serverinin hazırlanması

1. **PHP versiyası:** DirectAdmin → *Domain Setup / PHP Version Selector* → **PHP 8.2 və ya 8.3**.
   Lazımi genişlənmələr: `gd` (WebP dəstəyi ilə), `intl`, `mbstring`, `pdo_mysql`, `fileinfo`, `openssl`.
2. **MySQL:** *MySQL Management* → yeni verilənlər bazası və istifadəçi yaradın. Məlumatları qeyd edin.
3. **Email:** *E-mail Accounts* → məs. `noreply@hamid.agency` yaradın. SMTP: `mail.hamid.agency`, port `465` (SSL).
4. **SSL:** *SSL Certificates* → Let's Encrypt sertifikatı aktiv edin, "Force SSL" seçin.
5. **SSH (varsa):** *SSH Keys* bölməsində GitHub Actions üçün açar əlavə edin (bax: [bölmə 7](#7-github-actions-ilə-avtomatik-deploy)).

DirectAdmin-də standart qovluq quruluşu:

```
/home/İSTİFADƏÇİ/domains/hamid.agency/
├── public_html/     ← domenin "document root"-u (brauzer buranı görür)
└── laravel/         ← Laravel layihəsi bura yüklənəcək (DEPLOY_PATH)
```

**Laravel faylları heç vaxt birbaşa `public_html`-ə qoyulmamalıdır**, əks halda `.env`, `vendor` və s. internetdən əlçatan ola bilər. Yalnız `public/` qovluğu açıq olmalıdır.

---

## 6. public_html → Laravel /public yönləndirməsi

İki üsul var. Hansını seçməyinizdən asılı olmayaraq `public/index.php` hər iki halda avtomatik işləyir.

### Üsul A — Simvolik link (tövsiyə olunur, SSH lazımdır)

`public_html` qovluğu Laravel-in `public` qovluğuna link olur:

```bash
cd ~/domains/hamid.agency
mv public_html public_html_backup          # köhnə faylların ehtiyat nüsxəsi
ln -s laravel/public public_html
ls -la                                      # public_html -> laravel/public
```

GitHub Secrets-də `PUBLIC_PATH` **təyin etməyin**.

> Bəzi hostinqlərdə Apache simvolik linkləri izləməyə icazə vermir (`FollowSymLinks` / `SymLinksIfOwnerMatch`). Sayt 403 verirsə, Üsul B-dən istifadə edin.

### Üsul B — "Ayrılmış" quraşdırma (SSH olmadan da işləyir)

- Laravel `~/domains/hamid.agency/laravel/` qovluğuna yüklənir (`DEPLOY_PATH`)
- `public/` qovluğunun **məzmunu** `~/domains/hamid.agency/public_html/`-ə yüklənir (`PUBLIC_PATH`)
- `public_html/index.php` avtomatik olaraq `../laravel` qovluğunu tapır və `public_path()`-i `public_html`-ə yönləndirir

Bunun üçün GitHub Secrets-də `PUBLIC_PATH` təyin edin — workflow hər iki qovluğu özü yükləyəcək.

> Yüklənmiş şəkillər `public_html/uploads/` (Üsul A-da `laravel/public/uploads/`) qovluğunda saxlanılır və deploy zamanı silinmir.

---

## 7. GitHub Actions ilə avtomatik deploy

`.github/workflows/deploy.yml` — `main` branch-a hər push-da:

1. Testləri işə salır (uğursuzdursa deploy olunmur)
2. `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`
3. Faylları göndərir: **SSH varsa `rsync`**, yoxdursa **FTP** (`SamKirkland/FTP-Deploy-Action`)
4. **SSH ilə:** `php artisan migrate --force`, `config:cache`, `route:cache`, `view:cache` (deploy zamanı sayt qısa müddət maintenance rejimindədir)
   **FTP ilə:** eyni əmrlər qorunan HTTP endpoint (`POST /_deploy/post-deploy`, `X-Deploy-Token` başlığı) vasitəsilə icra olunur
5. Saytın `200` qaytardığını yoxlayır

Server məlumatları **yalnız GitHub Secrets-də** saxlanılır:
GitHub → repo → **Settings → Secrets and variables → Actions → New repository secret**

### SSH varsa

| Secret | Nümunə |
|---|---|
| `SSH_HOST` | `server.hostingprovider.az` və ya IP |
| `SSH_USER` | DirectAdmin istifadəçi adı |
| `SSH_KEY` | **Private** açarın tam mətni (`-----BEGIN OPENSSH PRIVATE KEY-----` ...) |
| `SSH_PORT` | (istəyə bağlı) standart `22` |
| `SSH_KNOWN_HOSTS` | (istəyə bağlı, tövsiyə olunur) `ssh-keyscan -p PORT HOST` nəticəsi |
| `DEPLOY_PATH` | `/home/İSTİFADƏÇİ/domains/hamid.agency/laravel` |
| `PUBLIC_PATH` | (yalnız Üsul B) `/home/İSTİFADƏÇİ/domains/hamid.agency/public_html` |

SSH açarı yaratmaq (öz kompüterinizdə):

```bash
ssh-keygen -t ed25519 -C "github-deploy-hamid-agency" -f hamid_deploy -N ""
# hamid_deploy.pub  → DirectAdmin → SSH Keys → "Paste key" (və ya ~/.ssh/authorized_keys)
# hamid_deploy      → GitHub Secret: SSH_KEY
```

### SSH yoxdursa (FTP)

| Secret | Nümunə |
|---|---|
| `FTP_SERVER` | `ftp.hamid.agency` |
| `FTP_USERNAME` | DirectAdmin FTP istifadəçisi |
| `FTP_PASSWORD` | FTP şifrəsi |
| `DEPLOY_PATH` | FTP kökündən yol, məs. `/domains/hamid.agency/laravel` |
| `PUBLIC_PATH` | **Tələb olunur** (Üsul B): `/domains/hamid.agency/public_html` |
| `DEPLOY_TOKEN` | Təsadüfi uzun mətn (ən azı 32 simvol); serverdəki `.env`-də **eyni** dəyər olmalıdır |

`DEPLOY_TOKEN` yaratmaq: `openssl rand -hex 32`

### İstəyə bağlı Variables (Settings → Variables)

| Variable | Standart | Təsvir |
|---|---|---|
| `APP_URL` | `https://hamid.agency` | Yoxlama və post-deploy üçün |
| `PHP_BIN` | `php` | SSH-da başqa PHP lazımdırsa, məs. `/usr/local/php82/bin/php` |
| `FTP_PROTOCOL` | `ftps` | Server FTPS dəstəkləmirsə `ftp` |
| `FTP_PORT` | `21` | |

> FTP ilə ilk deploy `vendor/` qovluğuna görə uzun çəkə bilər (5–15 dəq). Sonrakı deploy-larda yalnız dəyişən fayllar göndərilir.

---

## 8. İlk deploy — addım-addım

1. **Server:** [Bölmə 5](#5-directadmin-serverinin-hazırlanması)-dəki hazırlığı edin, `~/domains/hamid.agency/laravel/` qovluğunu yaradın.
2. **`.env` faylı:** `.env.example`-i kompüterinizdə kopyalayın, production dəyərlərini doldurun və serverə yükləyin:
   `~/domains/hamid.agency/laravel/.env` (File Manager və ya FTP ilə). `APP_KEY` yaratmaq üçün lokal olaraq:
   ```bash
   php artisan key:generate --show     # nəticəni .env-dəki APP_KEY= sətirinə yazın
   ```
   `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `NOTIFY_EMAIL`, `DB_*`, `MAIL_*` mütləq doldurulmalıdır. FTP üçün `DEPLOY_TOKEN` də.
3. **public_html:** [Bölmə 6](#6-public_html--laravel-public-yönləndirməsi)-dan üsul seçin.
4. **GitHub Secrets:** [Bölmə 7](#7-github-actions-ilə-avtomatik-deploy)-dəki dəyərləri əlavə edin.
5. **İlk deploy + seed:** GitHub → **Actions → Deploy → Run workflow** → `seed` seçimini işarələyin → Run.
   Bu, cədvəlləri yaradır, admin hesabını və başlanğıc məlumatları (xidmətlər, nümunə hosting paketləri, domen uzantıları, portfolio kateqoriyaları) əlavə edir.
6. **Yoxlama:** `https://hamid.agency` → `/az`-a yönləndirməlidir. `https://hamid.agency/admin` ilə daxil olun.
7. **Məzmun:** admin paneldə hosting və domen qiymətlərini real qiymətlərinizlə yeniləyin, portfolio layihələrinizi əlavə edin.
8. Bundan sonra `main`-ə hər push avtomatik deploy olunur.

> **SSH ilə əl ilə seed:** `cd ~/domains/hamid.agency/laravel && php artisan db:seed --force`
> Seeder-lər təkrar işlədilə bilər — mövcud məlumatların üzərinə yazmır (yalnız admin şifrəsi `.env`-dəkinə yenilənir).

---

## 9. Admin panel

- Ünvan: `/admin` (axtarış sistemlərindən gizlədilib: `noindex`, `robots.txt`)
- Hesab `.env`-dəki `ADMIN_EMAIL` / `ADMIN_PASSWORD` ilə seeder tərəfindən yaradılır. Şifrəni dəyişmək üçün `.env`-i yeniləyib `php artisan db:seed --class=AdminSeeder --force` işlədin.
- Hər məzmun növü üçün **AZ və EN sahələri yan-yanadır**; başlıq kimi əsas sahələr hər iki dildə məcburidir.
- **Şəkillər:** JPG/PNG/WebP/GIF (maks. 8 MB) → avtomatik **WebP**, portfolio üçün maks. 1200 px, xidmətlər üçün 1600 px, rəy fotoları 300 px.
- **Sifarişlər/mesajlar:** status filtri, axtarış, daxili qeyd, email/WhatsApp ilə cavab düymələri. Yeni müraciətlərin sayı menyuda göstərilir.
- **Domen uzantıları:** "Seçilmiş" uzantılar istifadəçi uzantısız axtarış etdikdə avtomatik yoxlanılır.

---

## 10. Domen provayderinin qoşulması

Arxitektura:

```
DomainController → DomainAvailabilityService → DomainRegistrar (interfeys)
                                                 ├─ WhoisAdapter        (standart, pulsuz)
                                                 ├─ ResellerClubAdapter
                                                 ├─ NamecheapAdapter
                                                 └─ WhmcsAdapter
```

Provayderi dəyişmək üçün yalnız `.env`:

```dotenv
DOMAIN_PROVIDER=resellerclub
RESELLERCLUB_AUTH_USERID=123456
RESELLERCLUB_API_KEY=...
RESELLERCLUB_SANDBOX=false
```

| Provayder | Qeydlər |
|---|---|
| `whois` | API açarı lazım deyil. Əvvəl DNS, sonra WHOIS (port 43) yoxlanılır. `.az` üçün açıq WHOIS serveri olmadığından DNS qeydi olmayan `.az` domenləri "Təsdiq tələb olunur" kimi göstərilir. Bəzi shared hostinqlərdə 43-cü port bağlıdır. |
| `resellerclub` | Server IP-si ResellerClub panelində whitelist edilməlidir. Əvvəl `RESELLERCLUB_SANDBOX=true` ilə yoxlayın. |
| `namecheap` | `NAMECHEAP_CLIENT_IP` = serverin IP-si (whitelist). Namecheap `.az` satmır. |
| `whmcs` | WHMCS → API Credentials; serverin IP-sini "API IP Access Restriction"-a əlavə edin. |

**Yeni provayder əlavə etmək:**
1. `app/Services/Domain/Adapters/` altında `DomainRegistrar` interfeysini tətbiq edən sinif yaradın (`name()` və `check(array $domains)` metodları).
2. `config/domain.php`-də parametrlərini əlavə edin (dəyərlər `env()` ilə).
3. `AppServiceProvider::register()` daxilindəki `match`-a əlavə edin.

Nəticələr `DOMAIN_CACHE_TTL` saniyə (standart 10 dəq) keşlənir; axtarış IP başına dəqiqədə 20 sorğu ilə məhdudlaşdırılıb. Provayder xəta versə, istifadəçi xəta görmür — nəticə "Təsdiq tələb olunur" olur və xəta log-a yazılır.

---

## 11. Tərcümələr və yeni mətnlər

- Bütün mətnlər `lang/az/*.php` və `lang/en/*.php` fayllarındadır. Kodda (Blade, kontroller) hardcode mətn yoxdur.
- URL seqmentləri: `lang/{az,en}/routes.php`. Dəyişdikdən sonra serverdə `php artisan route:cache`.
- Səhifələrin meta title/description-ları: `lang/{az,en}/seo.php`. Xidmət səhifələri üçün admin paneldəki SEO sahələri.
- Yeni açar əlavə etdikdə **hər iki** faylda əlavə edin — `TranslationCompletenessTest` testi çatışmayan və ya boş tərcümələri tapır.
- **Loqo:** hazırda mətn loqosudur (`resources/views/partials/header.blade.php` və `footer.blade.php`). Şəkil loqo hazır olduqda `public/images/`-ə qoyub həmin yerdə `<img>` ilə əvəz edin. OG şəkli: `public/images/og-default.png` (1200×630).

---

## 12. Testlər

```bash
php artisan test
```

Əhatə edir: hər iki dildə bütün səhifələr və SEO teqləri, kök yönləndirmə və dil cookie-si, sitemap, tərcümə tamlığı, əlaqə və sifariş formaları (validasiya, honeypot, vaxt yoxlaması, rate limit, email), domen axtarışı (normallaşdırma, keş, xəta halları), admin panel (giriş, icazələr, CRUD, WebP çevirmə, statuslar), deploy endpoint-i.

---

## 13. Problemlərin həlli

| Problem | Həll |
|---|---|
| **500 xətası** | `storage/logs/laravel-*.log`-a baxın. `storage/` və `bootstrap/cache/` qovluqları yazıla bilən olmalıdır (`chmod -R 775 storage bootstrap/cache`). |
| **"No application encryption key"** | Serverdəki `.env`-də `APP_KEY` boşdur. |
| **CSS/JS yüklənmir (404 /build/...)** | `public/build/` serverə yüklənməyib və ya `public_html` düzgün yönləndirilməyib (bölmə 6). |
| **403 Forbidden (Üsul A)** | Hostinq simvolik linklərə icazə vermir → Üsul B. |
| **Emaillər gəlmir** | `MAIL_*` və `NOTIFY_EMAIL`-i yoxlayın. Müraciətlər email getməsə belə bazada saxlanılır və admin paneldə görünür; xəta log-a yazılır. |
| **Şəkil yüklənmir** | PHP `gd` genişlənməsi WebP dəstəyi ilə aktiv olmalıdır; `upload_max_filesize` ≥ 8M. |
| **FTP deploy "post-deploy" 404** | Serverdəki `.env`-də `DEPLOY_TOKEN` yoxdur, 32 simvoldan qısadır və ya GitHub Secret ilə eyni deyil. |
| **`.env` dəyişikliyi təsir etmir** | `php artisan config:cache` (və ya yenidən deploy). |
| **Domen axtarışı həmişə "Təsdiq tələb olunur"** | WHOIS portu (43) bağlıdır → reseller API-yə keçin (bölmə 10). |
