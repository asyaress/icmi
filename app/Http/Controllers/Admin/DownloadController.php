<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDownloadRequest;
use App\Http\Requests\Admin\UpdateDownloadRequest;
use App\Models\Download;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $downloads = Download::query()
            ->with('author')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when(in_array($status, ['draft', 'published'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.downloads.index', compact('downloads', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.downloads.create');
    }

    public function store(StoreDownloadRequest $request): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');
        $file = $request->file('file');
        $filePath = $file->store('downloads', 'public');

        Download::query()->create([
            'author_id' => (int) $request->user()->id,
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Download::class),
            'description' => $request->input('description'),
            'file_path' => $filePath,
            'original_name' => (string) $file->getClientOriginalName(),
            'mime_type' => (string) $file->getMimeType(),
            'file_size' => (int) $file->getSize(),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : now())
                : null,
        ]);

        return redirect()->route('admin.downloads.index')->with('success', 'Surat berhasil ditambahkan.');
    }

    public function edit(Download $download): View
    {
        return view('admin.downloads.edit', compact('download'));
    }

    public function update(UpdateDownloadRequest $request, Download $download): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Download::class, 'slug', $download->id),
            'description' => $request->input('description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($download->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('file')) {
            if ($download->file_path) {
                Storage::disk('public')->delete($download->file_path);
            }

            $file = $request->file('file');
            $payload['file_path'] = $file->store('downloads', 'public');
            $payload['original_name'] = (string) $file->getClientOriginalName();
            $payload['mime_type'] = (string) $file->getMimeType();
            $payload['file_size'] = (int) $file->getSize();
        }

        $download->update($payload);

        return redirect()->route('admin.downloads.index')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        if ($download->file_path) {
            Storage::disk('public')->delete($download->file_path);
        }

        $download->delete();

        return redirect()->route('admin.downloads.index')->with('success', 'Surat berhasil dihapus.');
    }
}
