@csrf

<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">Judul Surat</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $download->title ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $download->slug ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $download->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $download->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label">Tanggal Publish <small class="text-muted">(opsional)</small></label>
        <input
            type="datetime-local"
            name="published_at"
            id="published_at"
            class="form-control"
            value="{{ old('published_at', isset($download) && $download->published_at ? $download->published_at->format('Y-m-d\\TH:i') : '') }}"
        >
    </div>

    <div class="col-md-6">
        <label for="file" class="form-label">File PDF</label>
        <input type="file" name="file" id="file" class="form-control" accept=".pdf,application/pdf" {{ isset($download) ? '' : 'required' }}>
        <small class="text-muted d-block mt-1">Maksimal 20MB, format PDF.</small>
        @if(isset($download))
            <small class="d-block mt-2">File saat ini: <strong>{{ $download->original_name }}</strong></small>
        @endif
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi <small class="text-muted">(opsional)</small></label>
        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $download->description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.downloads.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>
