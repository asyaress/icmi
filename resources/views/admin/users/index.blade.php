@extends('admin.layouts.app')

@section('title', 'Users Admin')
@section('page_title', 'Users Admin')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari nama/email" value="{{ $search }}">
                <button class="btn btn-outline-dark" type="submit">Cari</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->name ?? '-' }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at?->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
