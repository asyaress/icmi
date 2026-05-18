@extends('admin.layouts.app')

@section('title', 'Kelola Profil - Admin ICMI Kaltim')
@section('page_title', 'Kelola Profil Sekilas ICMI')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.profile-pages.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari judul/menu" value="{{ $search }}">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                </select>
                <button class="btn btn-outline-dark" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.profile-pages.create') }}" class="btn btn-primary">+ Tambah Halaman</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Label Menu</th>
                        <th>Slug</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $pages->firstItem() + $loop->index }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->menu_label }}</td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>{{ $page->menu_order }}</td>
                            <td>
                                @if($page->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $page->updated_at?->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('sekilas-icmi.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                <a href="{{ route('admin.profile-pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.profile-pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus halaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada halaman profil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pages->links() }}
    </div>
</div>
@endsection
