@extends('admin.layouts.app')

@section('title', 'Kategori Berita')
@section('page_title', 'Kategori Berita')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari kategori" value="{{ $search }}">
                <button class="btn btn-outline-dark" type="submit">Cari</button>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td>{{ $category->name }}</td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $category->created_at?->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $categories->links() }}</div>
    </div>
</div>
@endsection

