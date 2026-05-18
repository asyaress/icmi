<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaFileRequest;
use App\Models\MediaFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaManagerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', ''));

        $mediaFiles = MediaFile::query()
            ->with('uploader')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('original_name', 'like', "%{$search}%")
                        ->orWhere('mime_type', 'like', "%{$search}%");
                });
            })
            ->when($type !== '', function ($query) use ($type): void {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('admin.media-manager.index', compact('mediaFiles', 'search', 'type'));
    }

    public function store(StoreMediaFileRequest $request): RedirectResponse
    {
        foreach ((array) $request->file('files', []) as $file) {
            $path = $file->store('media-manager', 'public');
            MediaFile::query()->create([
                'uploader_id' => (int) $request->user()->id,
                'disk' => 'public',
                'path' => $path,
                'original_name' => (string) $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'extension' => strtolower((string) $file->getClientOriginalExtension()),
                'size' => (int) $file->getSize(),
                'type' => $this->detectType((string) $file->getClientMimeType(), strtolower((string) $file->getClientOriginalExtension())),
            ]);
        }

        return redirect()->route('admin.media-manager.index')->with('success', 'Media berhasil diupload.');
    }

    public function destroy(MediaFile $media_file): RedirectResponse
    {
        if ($media_file->path) {
            Storage::disk($media_file->disk)->delete($media_file->path);
        }
        $media_file->delete();

        return redirect()->route('admin.media-manager.index')->with('success', 'Media berhasil dihapus.');
    }

    public function library(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', ''));

        $items = MediaFile::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('original_name', 'like', "%{$search}%");
            })
            ->when($type !== '', function ($query) use ($type): void {
                $query->where('type', $type);
            })
            ->latest()
            ->limit(100)
            ->get()
            ->map(function (MediaFile $file): array {
                $url = $file->url;
                $isImage = $file->type === 'image';

                return [
                    'id' => $file->id,
                    'name' => $file->original_name,
                    'url' => $url,
                    'type' => $file->type,
                    'mime_type' => $file->mime_type,
                    'extension' => $file->extension,
                    'size' => $file->size,
                    'size_human' => $this->formatBytes((int) $file->size),
                    'is_image' => $isImage,
                    'preview_url' => $isImage ? $url : null,
                ];
            })
            ->values();

        return response()->json(['data' => $items]);
    }

    private function detectType(string $mimeType, string $extension): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if ($mimeType === 'application/pdf' || $extension === 'pdf') {
            return 'pdf';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'], true)) {
            return 'document';
        }

        return 'other';
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB'];
        $size = $bytes / 1024;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return number_format($size, 2) . ' ' . $units[$unitIndex];
    }
}
