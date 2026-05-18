# Technical Architecture

## Stack

1. Backend: Laravel (current project).
2. Database: MySQL/MariaDB.
3. Frontend: Blade + CSS theme override.
4. Cache: file/redis (sesuai environment).
5. Web server: Nginx/Apache.

## Arsitektur Aplikasi

## Layer
- `Presentation`: Blade views + components/partials.
- `Application`: Controllers + FormRequest + Services.
- `Domain`: Model Eloquent + business rules.
- `Infrastructure`: storage, queue, cache, mail.

## Modul Inti

1. Auth & Permission
- Login admin.
- Role-based access.

2. Content Engine
- Post/Article.
- Category/Tag.
- Author/Profile.

3. Media Manager
- Upload image/video thumbnail.
- Metadata media.

4. Taxonomy & Settings
- Menu config.
- Site settings.
- SEO defaults.

## Konvensi Teknis

1. Semua route admin pakai prefix `/admin`.
2. Semua endpoint mutasi pakai validasi `FormRequest`.
3. Slug unik per konten.
4. Soft delete untuk konten.
5. Audit fields wajib: `created_by`, `updated_by`.

## Kualitas dan Keamanan

1. CSRF aktif untuk semua form.
2. Rate limit untuk login dan endpoint sensitif.
3. Validasi upload mime + size.
4. Escape output default Blade.

