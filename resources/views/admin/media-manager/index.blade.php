@extends('admin.layouts.app')

@section('title', 'Media Manager')
@section('page_title', 'Media Manager')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.media-manager.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-md-9">
                <label for="files" class="form-label">Upload Media (foto, PDF, dokumen, audio/video)</label>
                <input
                    type="file"
                    class="form-control"
                    id="files"
                    name="files[]"
                    multiple
                    accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar,.mp4,.webm,.mp3,.wav"
                    required
                >
                <small class="text-muted">Maksimal 10MB per file. Upload dulu di sini, lalu panggil dari editor Summernote.</small>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Upload Media</button>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.media-manager.index') }}" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Cari nama file...">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Semua tipe</option>
                    @foreach(['image' => 'Image', 'pdf' => 'PDF', 'document' => 'Document', 'video' => 'Video', 'audio' => 'Audio', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-dark w-100" type="submit">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.media-manager.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            @forelse($mediaFiles as $file)
                <div class="col-md-4 col-lg-3">
                    <div class="border rounded p-2 h-100 d-flex flex-column">
                        <div class="media-preview mb-2">
                            @if($file->type === 'image')
                                <img src="{{ $file->url }}" alt="{{ $file->original_name }}" class="img-fluid rounded border">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 140px;">
                                    <span class="text-muted text-uppercase fw-semibold">{{ $file->extension ?: $file->type }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="small text-muted mb-1">{{ strtoupper($file->type) }} • {{ $file->mime_type }}</div>
                        <div class="fw-semibold mb-2" style="word-break: break-word;">{{ $file->original_name }}</div>
                        <div class="small text-muted mb-3">{{ number_format($file->size / 1024, 1) }} KB</div>
                        <div class="mt-auto d-grid gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary copy-url-btn" data-url="{{ $file->url }}">Copy URL</button>
                            <a href="{{ $file->url }}" target="_blank" class="btn btn-sm btn-outline-dark">Buka File</a>
                            <form action="{{ route('admin.media-manager.destroy', $file) }}" method="POST" onsubmit="return confirm('Hapus media ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border mb-0">Belum ada media. Upload file pertama Anda.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $mediaFiles->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.copy-url-btn').forEach(function (button) {
        button.addEventListener('click', async function () {
            const url = this.getAttribute('data-url') || '';
            if (!url) return;

            try {
                await navigator.clipboard.writeText(url);
                this.textContent = 'Copied!';
                setTimeout(() => { this.textContent = 'Copy URL'; }, 1200);
            } catch (error) {
                window.prompt('Copy URL ini:', url);
            }
        });
    });
});
</script>
@endpush
