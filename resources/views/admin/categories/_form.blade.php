@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nama Kategori</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="slug" class="form-label">Slug <small class="text-muted">(opsional)</small></label>
        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}">
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" @checked((bool) old('is_active', $category->is_active ?? true))>
            <label class="form-check-label" for="is_active">Kategori aktif</label>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>

