<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProfilePageRequest;
use App\Http\Requests\Admin\UpdateProfilePageRequest;
use App\Models\ProfilePage;
use App\Support\PublicCache;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfilePageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $pages = ProfilePage::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('menu_label', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['draft', 'published'], true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->orderBy('menu_order')
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.profile-pages.index', compact('pages', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.profile-pages.create');
    }

    public function store(StoreProfilePageRequest $request): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('profile-pages', 'public');
        }

        ProfilePage::query()->create([
            'title' => $title,
            'menu_label' => trim((string) $request->input('menu_label', '')) ?: Str::upper($title),
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, ProfilePage::class),
            'menu_order' => (int) $request->input('menu_order', 0),
            'excerpt' => $request->input('excerpt'),
            'content' => (string) $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'attachment_path' => $attachmentPath,
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : now())
                : null,
        ]);

        PublicCache::flush();

        return redirect()->route('admin.profile-pages.index')->with('success', 'Halaman profil berhasil dibuat.');
    }

    public function edit(ProfilePage $profile_page): View
    {
        return view('admin.profile-pages.edit', ['page' => $profile_page]);
    }

    public function update(UpdateProfilePageRequest $request, ProfilePage $profile_page): RedirectResponse
    {
        $title = $request->string('title')->toString();
        $slugInput = trim((string) $request->input('slug', ''));
        $status = (string) $request->input('status');

        $payload = [
            'title' => $title,
            'menu_label' => trim((string) $request->input('menu_label', '')) ?: Str::upper($title),
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $title, ProfilePage::class, 'slug', $profile_page->id),
            'menu_order' => (int) $request->input('menu_order', 0),
            'excerpt' => $request->input('excerpt'),
            'content' => (string) $request->input('content'),
            'seo_title' => $request->input('seo_title'),
            'seo_description' => $request->input('seo_description'),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->filled('published_at') ? $request->date('published_at') : ($profile_page->published_at ?? now()))
                : null,
        ];

        if ($request->hasFile('attachment')) {
            if ($profile_page->attachment_path) {
                Storage::disk('public')->delete($profile_page->attachment_path);
            }
            $payload['attachment_path'] = $request->file('attachment')->store('profile-pages', 'public');
        }

        $profile_page->update($payload);
        PublicCache::flush();

        return redirect()->route('admin.profile-pages.index')->with('success', 'Halaman profil berhasil diupdate.');
    }

    public function destroy(ProfilePage $profile_page): RedirectResponse
    {
        if ($profile_page->attachment_path) {
            Storage::disk('public')->delete($profile_page->attachment_path);
        }

        $profile_page->delete();
        PublicCache::flush();

        return redirect()->route('admin.profile-pages.index')->with('success', 'Halaman profil berhasil dihapus.');
    }
}
