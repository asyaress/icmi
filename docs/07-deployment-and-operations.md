# Deployment and Operations

## Environment

1. Local (developer).
2. Staging (UAT).
3. Production (live).

## Standar Deploy

1. Deploy via pipeline terstruktur (manual script atau CI/CD).
2. Jalankan migration dengan backup pre-migration.
3. Cache config/route/view setelah deploy.

## Checklist Deploy Minimum

1. `.env` production valid.
2. `APP_DEBUG=false`.
3. Queue worker aktif bila digunakan.
4. Storage link aktif.
5. SSL aktif.

## Backup dan Recovery

1. Backup database harian.
2. Backup file upload berkala.
3. Simpan minimal 7-30 versi backup.
4. Uji restore berkala di staging.

## Monitoring

1. Error logging aktif.
2. Uptime monitoring.
3. Alert untuk 5xx spikes.
4. Alert disk usage.

## Keamanan Operasional

1. Password policy admin.
2. Limit login attempts.
3. Update dependency berkala.
4. Review akses admin tiap bulan.

