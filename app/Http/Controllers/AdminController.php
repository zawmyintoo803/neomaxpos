<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSection;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with(['category', 'section'])->get();
        return view('admin.products.index', compact('products'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        $sections = ProductSection::all();
        return view('admin.products.create', compact('categories', 'sections'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'section_id' => 'required|exists:product_sections,id',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    // Show edit form
    public function edit(Product $product)
    {
        $categories = Category::all();
        $sections = ProductSection::all();
        return view('admin.products.edit', compact('product', 'categories', 'sections'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'section_id' => 'required|exists:product_sections,id',
            'price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
