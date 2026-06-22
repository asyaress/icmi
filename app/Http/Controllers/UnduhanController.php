<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UnduhanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $locale = app()->getLocale();

        $downloads = Download::query()
            ->with(['author', 'translations'])
            ->where('status', 'published')
            ->when($search !== '', function ($query) use ($search, $locale): void {
                $query->where(function ($innerQuery) use ($search, $locale): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");

                    if ($locale === 'en') {
                        $innerQuery->orWhereHas('translations', function ($translationQuery) use ($search): void {
                            $translationQuery
                                ->where('locale', 'en')
                                ->whereIn('field', ['title', 'description'])
                                ->where('value', 'like', "%{$search}%");
                        });
                    }
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('unduhan.index', compact('downloads', 'search'));
    }

    public function download(string $slug): StreamedResponse
    {
        $download = Download::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($download->file_path && Storage::disk('public')->exists($download->file_path), 404);

        return Storage::disk('public')->download($download->file_path, $download->original_name ?: basename($download->file_path));
    }

    public function preview(string $slug): BinaryFileResponse
    {
        $download = Download::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        abort_unless($download->file_path && Storage::disk('public')->exists($download->file_path), 404);

        $absolutePath = Storage::disk('public')->path($download->file_path);
        $filename = $download->original_name ?: basename($download->file_path);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
