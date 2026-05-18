# GitHub Actions CI/CD

## Workflow yang tersedia
- `.github/workflows/ci.yml`: jalan saat `push` dan `pull_request` ke `main`, menjalankan test Laravel.
- `.github/workflows/deploy.yml`: deploy otomatis saat `push` ke `main` dan bisa dijalankan manual via `workflow_dispatch`.

## Secrets yang perlu diset di GitHub repo
Masuk ke: `Settings > Secrets and variables > Actions > New repository secret`

- `DEPLOY_HOST`: host/IP server production
- `DEPLOY_USER`: username SSH server
- `DEPLOY_SSH_KEY`: private key SSH (format OpenSSH)
- `DEPLOY_PATH`: path folder project di server (contoh: `/var/www/icmi`)
- `DEPLOY_PORT`: port SSH (contoh: `22`)

## Alur deploy
1. Kode di-rsync ke server.
2. Jalankan `composer install --no-dev`.
3. Jalankan `php artisan migrate --force`.
4. Jalankan optimasi cache Laravel (`config`, `route`, `view`).

## Catatan
- Workflow deploy hanya jalan jika secrets deploy di atas terisi.
- File `.env` tidak di-overwrite dari GitHub Actions.
