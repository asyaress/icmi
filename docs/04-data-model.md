# Data Model (Draft)

## Entity Utama

1. `users`
- id, name, email, password, role_id, is_active, timestamps

2. `roles`
- id, name, description

3. `permissions`
- id, key, description

4. `role_permission`
- role_id, permission_id

5. `posts`
- id, type (`news|opinion|media-info`)
- title, slug, excerpt, content
- status (`draft|review|published|archived`)
- published_at
- author_id, created_by, updated_by
- seo_title, seo_description, featured_image
- is_featured, is_sticky
- timestamps, softDeletes

6. `categories`
- id, name, slug, type, parent_id, is_active

7. `tags`
- id, name, slug

8. `post_category`
- post_id, category_id

9. `post_tag`
- post_id, tag_id

10. `galleries`
- id, title, slug, description, cover_image, published_at, status

11. `gallery_items`
- id, gallery_id, media_id, caption, sort_order

12. `videos`
- id, title, slug, description, source_type, source_url, thumbnail, published_at, status

13. `pages`
- id, title, slug, content, status, seo_title, seo_description

14. `media`
- id, disk, path, filename, mime_type, size, uploaded_by

15. `settings`
- id, key, value (json/text), group

16. `activity_logs`
- id, user_id, action, entity_type, entity_id, payload, created_at

## Relasi Kunci

1. `users` 1..* `posts`
2. `posts` *..* `categories`
3. `posts` *..* `tags`
4. `galleries` 1..* `gallery_items`
5. `gallery_items` *..1 `media`

## Catatan

1. Slug harus unik per tabel.
2. Semua konten publishable punya field `status` dan `published_at`.
3. Gunakan soft delete untuk pemulihan konten.

