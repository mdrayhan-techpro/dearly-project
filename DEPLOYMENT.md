# Dearly — Production Deployment & Operations Guide

**Application:** Dearly ("Letters Find Their Way")  
**Target Environment:** Linux VPS / Docker / Managed PHP 8.2+ Hosting

---

## 1. System Requirements

- **PHP**: 8.2.0 or higher.
  - Required Extensions: `openssl`, `pdo`, `pdo_mysql` (or `pdo_sqlite`), `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`, `fileinfo`, `gd`, `intl`, `zip`.
- **Web Server**: Nginx or Apache with `mod_rewrite` enabled.
  - **Document Root**: Must point to `/path/to/Dearly-Project/public` (NEVER the project root).
- **Database**: MySQL 8.0+, MariaDB 10.5+, or SQLite 3.35+.
- **Node.js**: v18+ with `npm` (build time only for compiling frontend assets).
- **Process Manager**: Supervisor or systemd for background queue workers (`php artisan queue:work`).
- **Cron**: Standard crontab for Laravel Scheduler (`* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`).

---

## 2. Installation & Setup Steps

### Step 1: Clone & Install Dependencies
```bash
git clone <repository_url> dearly
cd dearly
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

### Step 2: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` to configure:
- `APP_NAME="Dearly"`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.com`
- Database credentials (`DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- Mail settings (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`)
- Google OAuth credentials (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`)

### Step 3: Run Database Migrations & Initial Seeders
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Step 4: Create Super Administrator
```bash
php artisan dearly:create-admin --name="Super Admin" --email="admin@yourdomain.com"
```

### Step 5: Symlink Public Storage
```bash
php artisan storage:link
```

### Step 6: Optimize Laravel Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3. Background Queue Worker Configuration

Use **Supervisor** to ensure background jobs (match emails, delivery processing, read receipt dispatches) run reliably:

`/etc/supervisor/conf.d/dearly-worker.conf`:
```ini
[program:dearly-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/dearly/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/dearly/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 4. Cron Scheduler Configuration

Add the scheduler to the web server's crontab:
```bash
crontab -e -u www-data
```
Add line:
```cron
* * * * * cd /var/www/dearly && php artisan schedule:run >> /dev/null 2>&1
```
