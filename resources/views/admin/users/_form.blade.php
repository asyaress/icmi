@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">Password @isset($user)<small class="text-muted">(kosongkan jika tidak diubah)</small>@endisset</label>
        <input type="password" name="password" id="password" class="form-control" @empty($user)required@endempty>
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" @empty($user)required@endempty>
    </div>

    <div class="col-md-6">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" id="role_id" class="form-select" required>
            <option value="">Pilih role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected((int) old('role_id', $user->role_id ?? 0) === $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" @checked((bool) old('is_active', $user->is_active ?? true))>
            <label class="form-check-label" for="is_active">User aktif</label>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark">Kembali</a>
</div>
