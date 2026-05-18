<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Support\SlugGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $name = $request->string('name')->toString();
        $slugInput = trim((string) $request->input('slug', ''));

        Category::query()->create([
            'name' => $name,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $name, Category::class),
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $name = $request->string('name')->toString();
        $slugInput = trim((string) $request->input('slug', ''));

        $category->update([
            'name' => $name,
            'slug' => SlugGenerator::generate($slugInput !== '' ? $slugInput : $name, Category::class, 'slug', $category->id),
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena sudah dipakai berita.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

