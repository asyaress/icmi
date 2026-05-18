@extends('admin.layouts.app')

@section('title', 'Manajemen Galeri')
@section('page_title', 'Manajemen Galeri')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.galleries.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari judul galeri" value="{{ $search }}">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                </select>
                <button class="btn btn-outline-dark" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">Tambah Galeri</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Jumlah Foto</th>
                        <th>Status</th>
                        <th>Publish At</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $gallery)
                        <tr>
                            <td>{{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $gallery->title }}</div>
                                <small class="text-muted"><code>{{ $gallery->slug }}</code></small>
                            </td>
                            <td>{{ $gallery->author->name ?? '-' }}</td>
                            <td>{{ $gallery->items->count() }}</td>
                            <td>
                                @if($gallery->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $gallery->published_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($gallery->status === 'published')
                                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('galeri.show', $gallery->slug) }}">Lihat</a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.galleries.edit', $gallery) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" onsubmit="return confirm('Hapus galeri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada galeri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $galleries->links() }}</div>
    </div>
</div>
@endsection
