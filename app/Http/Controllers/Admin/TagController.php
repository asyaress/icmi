<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Models\Tag;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $tags = Tag::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tags.index', compact('tags', 'search'));
    }

    public function create(): View
    {
        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $name = $request->string('name')->toString();
        $slugInput = trim((string) $request->input('slug', ''));

        Tag::query()->create([
            'name' => $name,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $name, Tag::class),
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil dibuat.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $name = $request->string('name')->toString();
        $slugInput = trim((string) $request->input('slug', ''));

        $tag->update([
            'name' => $name,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $name, Tag::class, 'slug', $tag->id),
        ]);

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil diupdate.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil dihapus.');
    }
}

