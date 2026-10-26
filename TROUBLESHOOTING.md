# Dearly — Troubleshooting & Operational Runbook

This guide covers common development, operational, and deployment issues for the Dearly platform.

---

## 1. Environment & Setup Issues

### Issue: "Application Control policy has blocked this file" (Windows Device Guard)
- **Cause**: Windows Device Guard or AppLocker policy blocking local executables (e.g. `C:\xampp\php\php.exe`).
- **Fix**: Use the verified, signed PHP executable installed via Windows Package Manager (WinGet):
  `C:\Users\<User>\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe`
  Ensure this path is placed first in `$env:PATH`.

### Issue: Missing PHP Extensions (`pdo_sqlite`, `zip`, `intl`)
- **Fix**: Open the active `php.ini` and verify that the following lines are uncommented:
  ```ini
  extension=pdo_sqlite
  extension=sqlite3
  extension=zip
  extension=intl
  extension=curl
  extension=fileinfo
  extension=gd
  extension=mbstring
  extension=openssl
  ```

---

## 2. Authentication & Google OAuth Issues

### Issue: "Missing or Invalid State in OAuth Callback"
- **Cause**: Session cookie mismatch, HTTPS/HTTP protocol switching, or browser cookie restrictions.
- **Fix**:
  1. Ensure `APP_URL` in `.env` matches the exact URL and scheme used in the browser.
  2. For local testing without active Google Cloud credentials, use the built-in Developer Auth Bridge: navigate to `/login` and select a pre-configured test persona.

---

## 3. Letter Sending & Queue Issues

### Issue: "Letters are saved in drafts but not showing in recipient inbox"
- **Cause**: Letters must go through the "Fold My Letter" and sealing workflow. If queue workers are not running and `QUEUE_CONNECTION=database` is configured, asynchronous jobs will wait in the `jobs` table.
- **Fix**:
  1. In development, run `php artisan queue:work` or set `QUEUE_CONNECTION=sync` in `.env`.
  2. Verify that the sender and recipient are not mutually blocked.
  3. Ensure daily sending limits have not been reached (`daily_sending_counters`).

---

## 4. Multilingual & RTL Display Issues

### Issue: Arabic (`ar`) layout not mirroring or fonts breaking
- **Cause**: Missing `dir="rtl"` attribute or cache holding stale CSS.
- **Fix**:
  1. The layout dynamically sets `<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">`.
  2. Run `npm run build` and `php artisan view:clear` to flush cached templates.
