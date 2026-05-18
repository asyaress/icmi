@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Tag</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tag->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $tag->slug ?? '') }}">
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>

