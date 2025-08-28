<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index(Request $request)
    {
        $viewType = $request->input('view', 'cards');

        $q = trim($request->input('search', ''));

        $query = Category::query()
            ->when($q, fn($qBuilder, $term) => $qBuilder->where('name', 'like', "%{$term}%"))
            ->latest();

        $categories = $query->paginate(5)->appends($request->except('page'));

        return view('categories.index', compact('categories', 'viewType'));
    }


    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)    
    {               
        $request->user()->categories()->create($request->validated());    
        return redirect()->route('categories.index')->with('success', 'Categoría creada');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {        
        $category->update($request->validated());
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
