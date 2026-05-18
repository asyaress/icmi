@csrf
@include('admin.partials.summernote')

<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">Judul Video</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $video->title ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $video->slug ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $video->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $video->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label">Tanggal Publish <small class="text-muted">(opsional)</small></label>
        <input
            type="datetime-local"
            name="published_at"
            id="published_at"
            class="form-control"
            value="{{ old('published_at', isset($video) && $video->published_at ? $video->published_at->format('Y-m-d\\TH:i') : '') }}"
        >
    </div>

    <div class="col-md-6">
        <label for="thumbnail" class="form-label">Thumbnail <small class="text-muted">(opsional)</small></label>
        <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        @if(!empty($video?->thumbnail))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$video->thumbnail) }}" alt="thumbnail" style="max-height: 120px; border:1px solid #e5e7eb;">
            </div>
        @endif
    </div>

    <div class="col-12">
        <label for="youtube_url" class="form-label">URL YouTube</label>
        <input
            type="text"
            name="youtube_url"
            id="youtube_url"
            class="form-control"
            placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx"
            value="{{ old('youtube_url', $video->youtube_url ?? '') }}"
            required
        >
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea name="description" id="description" rows="5" class="form-control js-summernote-lite">{{ old('description', $video->description ?? '') }}</textarea>
        <small class="text-muted d-block mt-2">Bisa sisipkan file/gambar dari tombol <strong>Media</strong>.</small>
    </div>

    <div class="col-12">
        <hr>
        <h6 class="mb-2">SEO</h6>
    </div>

    <div class="col-md-6">
        <label for="seo_title" class="form-label">Meta Title <small class="text-muted">(opsional)</small></label>
        <input type="text" name="seo_title" id="seo_title" class="form-control" maxlength="255" value="{{ old('seo_title', $video->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="seo_description" class="form-label">Meta Description <small class="text-muted">(opsional)</small></label>
        <textarea name="seo_description" id="seo_description" rows="2" class="form-control" maxlength="320">{{ old('seo_description', $video->seo_description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>
