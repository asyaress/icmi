@extends('admin.layouts.app')

@section('title', 'Manajemen Unduhan Surat')
@section('page_title', 'Manajemen Unduhan Surat')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.downloads.index') }}" class="d-flex gap-2">
                <input class="form-control" type="search" name="q" placeholder="Cari judul surat" value="{{ $search }}">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                </select>
                <button class="btn btn-outline-dark" type="submit">Filter</button>
            </form>
            <a href="{{ route('admin.downloads.create') }}" class="btn btn-primary">Tambah Surat</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Status</th>
                        <th>Publish At</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downloads as $download)
                        <tr>
                            <td>{{ $loop->iteration + ($downloads->currentPage() - 1) * $downloads->perPage() }}</td>
                            <td>
                                <div class="fw-semibold">{{ $download->title }}</div>
                                <small class="text-muted"><code>{{ $download->slug }}</code></small>
                            </td>
                            <td>{{ $download->original_name }}</td>
                            <td>{{ number_format(((int) $download->file_size) / 1024, 1) }} KB</td>
                            <td>
                                @if($download->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $download->published_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($download->status === 'published')
                                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('unduhan.download', $download->slug) }}">Unduh</a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.downloads.edit', $download) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.downloads.destroy', $download) }}" onsubmit="return confirm('Hapus surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $downloads->links() }}</div>
    </div>
</div>
@endsection
