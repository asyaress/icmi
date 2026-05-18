@csrf
@include('admin.partials.summernote')

<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">Judul Galeri</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $gallery->title ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $gallery->slug ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $gallery->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $gallery->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label">Tanggal Publish <small class="text-muted">(opsional)</small></label>
        <input
            type="datetime-local"
            name="published_at"
            id="published_at"
            class="form-control"
            value="{{ old('published_at', isset($gallery) && $gallery->published_at ? $gallery->published_at->format('Y-m-d\\TH:i') : '') }}"
        >
    </div>

    <div class="col-md-6">
        <label for="cover_image" class="form-label">Cover Galeri</label>
        <input type="file" name="cover_image" id="cover_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        @if(!empty($gallery?->cover_image))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$gallery->cover_image) }}" alt="cover" style="max-height: 120px; border:1px solid #e5e7eb;">
            </div>
        @endif
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea name="description" id="description" rows="4" class="form-control js-summernote-lite">{{ old('description', $gallery->description ?? '') }}</textarea>
        <small class="text-muted d-block mt-2">Gunakan tombol <strong>Media</strong> untuk sisipkan dokumen pendukung jika perlu.</small>
    </div>

    <div class="col-12">
        <hr>
        <h6 class="mb-2">SEO</h6>
    </div>

    <div class="col-md-6">
        <label for="seo_title" class="form-label">Meta Title <small class="text-muted">(opsional)</small></label>
        <input type="text" name="seo_title" id="seo_title" class="form-control" maxlength="255" value="{{ old('seo_title', $gallery->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="seo_description" class="form-label">Meta Description <small class="text-muted">(opsional)</small></label>
        <textarea name="seo_description" id="seo_description" rows="2" class="form-control" maxlength="320">{{ old('seo_description', $gallery->seo_description ?? '') }}</textarea>
    </div>

    @if(isset($gallery) && $gallery->items->isNotEmpty())
        <div class="col-12">
            <label class="form-label">Foto Saat Ini <small class="text-muted">(centang untuk hapus)</small></label>
            <div class="row g-2">
                @foreach($gallery->items as $item)
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <img src="{{ asset('storage/'.$item->image_path) }}" alt="gallery item" class="img-fluid rounded mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_item_ids[]" value="{{ $item->id }}" id="remove-item-{{ $item->id }}">
                                <label class="form-check-label" for="remove-item-{{ $item->id }}">Hapus foto ini</label>
                            </div>
                            @if($item->caption)
                                <small class="text-muted d-block mt-1">{{ $item->caption }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="col-12">
        <label for="images" class="form-label">Tambah Foto Galeri (multi-image)</label>
        <input type="file" name="images[]" id="images" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
        <small class="text-muted">Upload beberapa foto sekaligus. Caption opsional bisa diisi per urutan foto.</small>
    </div>

    <div class="col-12">
        <label class="form-label">Caption Foto Baru (opsional)</label>
        <div class="row g-2">
            @for($i = 0; $i < 6; $i++)
                <div class="col-md-6">
                    <input
                        type="text"
                        name="captions[]"
                        class="form-control"
                        placeholder="Caption foto ke-{{ $i + 1 }}"
                        value="{{ old('captions.'.$i, '') }}"
                    >
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>
