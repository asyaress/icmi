# Sprint 6 Stabilization Report

Tanggal: 17 Mei 2026  
Status: Selesai

## 1. Hardening

1. Login admin sudah memakai rate limiting `5 request/menit` per kombinasi `email + IP`.
2. Security headers aktif global:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- `Strict-Transport-Security` saat HTTPS
3. Route `robots.txt` tersedia dan menunjuk `sitemap.xml`.

## 2. Cache & Optimasi

1. Public cache layer berbasis versioning (`App\Support\PublicCache`) aktif.
2. Auto invalidation cache saat perubahan model konten/setting melalui observer.
3. Caching diterapkan di endpoint berat:
- Home (`/`)
- Sitemap (`/sitemap.xml`)
- Detail konten (berita, opini, info media, galeri, video)
4. Index performa query ditambahkan pada tabel konten utama.

## 3. Operasional

Perintah baru:

1. `php artisan icmi:cache:flush`  
Invalidasi public cache.

2. `php artisan icmi:cache:warm`  
Preload route publik penting setelah deploy.

## 4. Quality Gate

1. Test suite lulus penuh.
2. Tidak ada blocker diketahui pada fungsi inti publik/admin.
