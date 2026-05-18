@csrf
@include('admin.partials.summernote')

<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">Judul Berita</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $post->slug ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="category_id" class="form-label">Kategori</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Pilih kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $post->category_id ?? 0) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select" required>
            <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $post->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="published_at" class="form-label">Tanggal Publish <small class="text-muted">(opsional)</small></label>
        <input
            type="datetime-local"
            name="published_at"
            id="published_at"
            class="form-control"
            value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\\TH:i') : '') }}"
        >
    </div>

    <div class="col-12">
        <label for="excerpt" class="form-label">Ringkasan</label>
        <textarea name="excerpt" id="excerpt" rows="3" class="form-control">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Konten</label>
        <textarea name="content" id="content" class="form-control js-summernote" required>{{ old('content', $post->content ?? '') }}</textarea>
        <small class="text-muted d-block mt-2">Gunakan tombol <strong>Media</strong> di toolbar untuk panggil file dari Media Manager.</small>
    </div>

    <div class="col-12">
        <hr>
        <h6 class="mb-2">SEO</h6>
    </div>

    <div class="col-md-6">
        <label for="seo_title" class="form-label">Meta Title <small class="text-muted">(opsional)</small></label>
        <input type="text" name="seo_title" id="seo_title" class="form-control" maxlength="255" value="{{ old('seo_title', $post->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label for="seo_description" class="form-label">Meta Description <small class="text-muted">(opsional)</small></label>
        <textarea name="seo_description" id="seo_description" rows="2" class="form-control" maxlength="320">{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label for="tag_ids" class="form-label">Tag</label>
        <select name="tag_ids[]" id="tag_ids" class="form-select" multiple size="6">
            @php($selectedTagIds = old('tag_ids', isset($post) ? $post->tags->pluck('id')->all() : []))
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTagIds, true))>{{ $tag->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu tag.</small>
    </div>

    <div class="col-12">
        <label for="featured_image" class="form-label">Featured Image</label>
        <input type="file" name="featured_image" id="featured_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        @if(!empty($post?->featured_image))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$post->featured_image) }}" alt="featured" style="max-height: 120px; border:1px solid #e5e7eb;">
            </div>
        @endif
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a
        href="{{ $cancelRoute ?? (request()->routeIs('admin.media-info.*') ? route('admin.media-info.index') : (request()->routeIs('admin.opinions.*') ? route('admin.opinions.index') : route('admin.posts.index'))) }}"
        class="btn btn-outline-dark"
    >
        Kembali
    </a>
</div>
