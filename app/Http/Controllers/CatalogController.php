<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        return view('catalog.index', [
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'products' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->when($request->filled('category'), fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) => $categoryQuery->where('slug', $request->string('category')->toString()),
                ))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('catalog.show', [
            'product' => $product->load('category'),
        ]);
    }
}
