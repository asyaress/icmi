<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use App\Support\SlugGenerator;
use App\Support\YouTube;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $videos = Video::query()
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

        return view('admin.videos.index', compact('videos', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.videos.create');
    }

    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');
        $youtubeUrl = (string) $request->input('youtube_url');
        $youtubeId = YouTube::extractId($youtubeUrl);

        if ($youtubeId === null) {
            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid.'])->withInput();
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        Video::query()->create([
            'author_id' => (int) $request->user()->id,
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Video::class),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'youtube_url' => $youtubeUrl,
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnailPath,
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : now())
                : null,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dibuat.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');
        $youtubeUrl = (string) $request->input('youtube_url');
        $youtubeId = YouTube::extractId($youtubeUrl);

        if ($youtubeId === null) {
            return back()->withErrors(['youtube_url' => 'URL YouTube tidak valid.'])->withInput();
        }

        $payload = [
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Video::class, 'slug', $video->id),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'youtube_url' => $youtubeUrl,
            'youtube_id' => $youtubeId,
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($video->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $payload['thumbnail'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        $video->update($payload);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diupdate.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }
}
