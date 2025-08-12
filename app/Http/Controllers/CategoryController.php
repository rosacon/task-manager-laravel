<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Task;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('search', ''));

        $query = Category::query()
            ->when($q, fn($qBuilder, $term) => $qBuilder->where('name', 'like', "%{$term}%"))
            ->latest();

        $categories = $query->paginate(50)->appends($request->except('page'));

        return view('categories.index', compact('categories'));
    }


    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría creada');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}
