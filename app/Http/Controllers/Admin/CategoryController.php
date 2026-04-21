<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Soft delete
        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada. Puedes restaurarla desde la papelera.');
    }

    public function trashed()
    {
        $categories = Category::onlyTrashed()->latest()->paginate(10);
        return view('admin.categories.trashed', compact('categories'));
    }

    public function restore($id)
    {
        Category::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.categories.trashed')->with('success', 'Categoría restaurada.');
    }

    public function forceDelete($id)
    {
        Category::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.categories.trashed')->with('success', 'Categoría eliminada permanentemente.');
    }
}
