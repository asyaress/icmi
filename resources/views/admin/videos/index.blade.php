@extends('admin.layouts.app')

@section('title', 'Manajemen ICMI TV')
@section('page_title', 'Manajemen ICMI TV')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.videos.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari judul video" value="{{ $search }}">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                </select>
                <button class="btn btn-outline-dark" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">Tambah Video</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>YouTube ID</th>
                        <th>Status</th>
                        <th>Publish At</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td>{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $video->title }}</div>
                                <small class="text-muted"><code>{{ $video->slug }}</code></small>
                            </td>
                            <td>{{ $video->author->name ?? '-' }}</td>
                            <td><code>{{ $video->youtube_id }}</code></td>
                            <td>
                                @if($video->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $video->published_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($video->status === 'published')
                                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('icmi-tv.show', $video->slug) }}">Lihat</a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.videos.edit', $video) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" onsubmit="return confirm('Hapus video ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada video.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $videos->links() }}</div>
    </div>
</div>
@endsection
