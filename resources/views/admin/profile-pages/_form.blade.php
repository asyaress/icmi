@csrf
@include('admin.partials.summernote')

<div class="row g-3">
    <div class="col-md-8">
        <label for="title" class="form-label">Judul Halaman</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $page->title ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label for="menu_order" class="form-label">Urutan Menu</label>
        <input type="number" min="0" name="menu_order" id="menu_order" class="form-control" value="{{ old('menu_order', $page->menu_order ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label for="menu_label" class="form-label">Label Menu <small class="text-muted">(opsional)</small></label>
        <input type="text" name="menu_label" id="menu_label" class="form-control" value="{{ old('menu_label', $page->menu_label ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $page->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $page->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label">Tanggal Publish <small class="text-muted">(opsional)</small></label>
        <input
            type="datetime-local"
            name="published_at"
            id="published_at"
            class="form-control"
            value="{{ old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\\TH:i') : '') }}"
        >
    </div>

    <div class="col-12">
        <label for="excerpt" class="form-label">Ringkasan <small class="text-muted">(opsional)</small></label>
        <textarea name="excerpt" id="excerpt" rows="3" class="form-control">{{ old('excerpt', $page->excerpt ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Konten Halaman</label>
        <textarea name="content" id="content" class="form-control js-summernote" required>{{ old('content', $page->content ?? '') }}</textarea>
        <small class="text-muted d-block mt-2">Gunakan editor di atas untuk format konten. Konten akan disimpan sebagai HTML.</small>
    </div>

    <div class="col-md-6">
        <label for="attachment" class="form-label">Lampiran <small class="text-muted">(PDF/DOC, opsional)</small></label>
        <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.doc,.docx">
        @if(!empty($page?->attachment_path))
            <small class="d-block mt-2">
                Lampiran saat ini:
                <a href="{{ asset('storage/'.$page->attachment_path) }}" target="_blank">Lihat file</a>
            </small>
        @endif
    </div>

    <div class="col-md-6">
        <label for="seo_title" class="form-label">Meta Title <small class="text-muted">(opsional)</small></label>
        <input type="text" name="seo_title" id="seo_title" class="form-control" maxlength="255" value="{{ old('seo_title', $page->seo_title ?? '') }}">
    </div>

    <div class="col-12">
        <label for="seo_description" class="form-label">Meta Description <small class="text-muted">(opsional)</small></label>
        <textarea name="seo_description" id="seo_description" rows="2" class="form-control" maxlength="320">{{ old('seo_description', $page->seo_description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.profile-pages.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>
