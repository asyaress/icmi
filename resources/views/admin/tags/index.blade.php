@extends('admin.layouts.app')

@section('title', 'Tag Berita')
@section('page_title', 'Tag Berita')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.tags.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari tag" value="{{ $search }}">
                <button class="btn btn-outline-dark" type="submit">Cari</button>
            </form>
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Tambah Tag</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Dibuat</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>{{ $loop->iteration + ($tags->currentPage() - 1) * $tags->perPage() }}</td>
                            <td>{{ $tag->name }}</td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>{{ $tag->created_at?->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Hapus tag ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada tag.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $tags->links() }}</div>
    </div>
</div>
@endsection

