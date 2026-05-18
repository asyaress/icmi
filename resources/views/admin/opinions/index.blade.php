@extends('admin.layouts.app')

@section('title', 'Opini & Tokoh')
@section('page_title', 'Opini & Tokoh')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.opinions.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari judul opini/tokoh" value="{{ $search }}">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                </select>
                <button class="btn btn-outline-dark" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.opinions.create') }}" class="btn btn-primary">Tambah Opini/Tokoh</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Publish At</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $post->title }}</div>
                                <small class="text-muted"><code>{{ $post->slug }}</code></small>
                            </td>
                            <td>{{ $post->category->name ?? '-' }}</td>
                            <td>{{ $post->author->name ?? '-' }}</td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $post->published_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($post->status === 'published')
                                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('opini-tokoh.show', $post->slug) }}">Lihat</a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.opinions.edit', $post) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.opinions.destroy', $post) }}" onsubmit="return confirm('Hapus opini ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada opini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $posts->links() }}</div>
    </div>
</div>
@endsection
