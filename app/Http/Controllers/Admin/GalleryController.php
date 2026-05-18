<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $galleries = Gallery::query()
            ->with(['author', 'items'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when(in_array($status, ['draft', 'published'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery = Gallery::query()->create([
            'author_id' => (int) $request->user()->id,
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Gallery::class),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'cover_image' => $coverPath,
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : now())
                : null,
        ]);

        $this->addGalleryItems(
            $gallery,
            (array) $request->file('images', []),
            (array) $request->input('captions', [])
        );

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil dibuat.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', [
            'gallery' => $gallery->load('items'),
        ]);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'title' => $title,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, Gallery::class, 'slug', $gallery->id),
            'description' => $request->input('description'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($gallery->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $payload['cover_image'] = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery->update($payload);

        $removeItemIds = array_map('intval', (array) $request->input('remove_item_ids', []));
        if ($removeItemIds !== []) {
            $itemsToRemove = $gallery->items()->whereIn('id', $removeItemIds)->get();
            foreach ($itemsToRemove as $item) {
                Storage::disk('public')->delete($item->image_path);
                $item->delete();
            }
        }

        $this->addGalleryItems(
            $gallery,
            (array) $request->file('images', []),
            (array) $request->input('captions', []),
            (int) $gallery->items()->max('sort_order') + 1
        );

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil diupdate.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        foreach ($gallery->items as $item) {
            Storage::disk('public')->delete($item->image_path);
        }
        $gallery->items()->delete();

        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Galeri berhasil dihapus.');
    }

    private function addGalleryItems(Gallery $gallery, array $images, array $captions, int $sortStart = 1): void
    {
        $order = $sortStart;

        foreach ($images as $index => $image) {
            $path = $image->store('galleries/items', 'public');
            $gallery->items()->create([
                'image_path' => $path,
                'caption' => $captions[$index] ?? null,
                'sort_order' => $order,
            ]);
            $order++;
        }
    }
}
