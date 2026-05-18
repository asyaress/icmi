# Production Runbook

## A. Pre-Deploy

1. Pastikan branch release sudah melalui UAT.
2. Backup database dan `storage/app/public`.
3. Pastikan `.env` production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` benar
- driver `cache/session/queue` sesuai infrastruktur

## B. Deploy Steps

1. Pull code terbaru.
2. Install dependency:
```bash
composer install --no-dev --optimize-autoloader
```
3. Jalankan migration:
```bash
php artisan migrate --force
```
4. Build cache framework:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
5. Warm public cache:
```bash
php artisan icmi:cache:warm
```
6. Pastikan `storage:link` aktif:
```bash
php artisan storage:link
```

## C. Post-Deploy Smoke Test

1. Cek halaman publik:
- `/`
- `/berita`
- `/opini-tokoh`
- `/info-media`
- `/galeri`
- `/icmi-tv`
- `/sitemap.xml`
- `/robots.txt`
2. Cek login admin dan dashboard.
3. Cek create/edit konten sederhana lalu lihat di publik.

## D. Rollback Minimum

1. Rollback kode ke release stabil sebelumnya.
2. Jika migration bermasalah, restore backup DB.
3. Jalankan:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

## E. Monitoring Hypercare (7 Hari)

1. Pantau log error aplikasi harian.
2. Pantau trafik 4xx/5xx.
3. Cek performa endpoint home dan listing konten.
4. Triage bug maksimal H+1 untuk issue blocker.
