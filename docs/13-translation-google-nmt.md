# Google NMT Auto Translation (Background + Quota Guard)

## Tujuan
- Auto translate konten `id -> en` lewat queue background.
- Tidak ada latency translate saat halaman dibuka.
- Stop otomatis saat kuota bulanan `500.000` karakter sudah habis.

## Environment
Tambahkan di `.env`:

```env
GOOGLE_APPLICATION_CREDENTIALS=/home/icmi-kaltim/apps/icmi/storage/app/secure/icmi-xxxx.json
GCP_PROJECT_ID=your-project-id
GCP_TRANSLATE_REGION=global
GCP_TRANSLATE_MODEL=general/nmt

TRANSLATION_ENABLED=true
TRANSLATION_PROVIDER=google_nmt
TRANSLATION_SOURCE_LOCALE=id
TRANSLATION_TARGET_LOCALES=en
TRANSLATION_QUEUE=translations
TRANSLATION_MONTHLY_LIMIT=500000
```

## Migrasi
```bash
php artisan migrate
```

## Worker
Jalankan worker agar job translate diproses:

```bash
php artisan queue:work --queue=translations,default
```

## Command Penting
- Cek usage:
```bash
php artisan icmi:translate:usage
```

- Test translate (sekaligus hit counter quota):
```bash
php artisan icmi:translate:test "Halo dunia" --from=id --to=en
```

- Sinkron semua konten published lama:
```bash
php artisan icmi:translate:sync
```

- Proses sinkron langsung (tanpa queue):
```bash
php artisan icmi:translate:sync --now
```

## Catatan
- Saat limit bulanan tercapai, sistem otomatis menolak request translate baru.
- Konten tetap tampil normal dengan fallback bahasa sumber (Indonesia).
- Perubahan konten via admin akan otomatis antre translate ulang.

