<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show index page
    public function index()
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->select(
                    'products.id',
                    'products.product_code',
                    'products.product_name',
                    'products.price',
                    'products.stock',
                    'products.expiry_date',
                    'categories.category_name',
                    'units.unit_name'
                )
                ->get();
        $categories = Category::all();
        $units = Unit::all();
       // dd($products->all());
        return view('admin.product.index', compact('products','categories','units'));
    }
    public function create(){
        $categories = Category::all();
        $units = Unit::all();
        return view('admin.product.create',compact('categories','units'));
    }
    // Return products JSON for AJAX
    public function list()
    {
        $products = Product::with(['category', 'unit'])->get()->map(function($p){
            return [
                'id' => $p->id,
                'product_name' => $p->product_name,
                'category_name' => $p->category->category_name ?? '',
                'unit_name' => $p->unit->unit_name ?? '',
                'stock' => $p->stock,
                'expiry_date' => $p->expiry_date,
                'barcode' => $p->barcode,
                'image_url' => $p->image ? asset('storage/'.$p->image) : null,
            ];
        });

        return response()->json($products);
    }

    // Save product (create or update)
    // Delete product
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if($product->image){
            Storage::delete($product->image);
        }
        $product->delete();

        return response()->json(['success' => true]);
    }
    public function autocompleteInvoice(Request $request)
    {
        $term = $request->get('term','');
        $invoices = Sale::where('invoice_no','like','%'.$term.'%')
            ->limit(10)
            ->pluck('invoice_no');

        return response()->json($invoices);
    }
    public function store(Request $request){
    logger($request->all());
    $request->validate([
        'product_name'=>'required',
        'product_code'=>'nullable',
        'category_id'=>'required',
        'unit_id'=>'required',
        'price'=>'nullable',
        'stock'=>'nullable',
        'image'=>'nullable|image',
    ]);

    $product = new Product($request->all());

    if($request->hasFile('image')){
        $path = $request->file('image')->store('products','public');
        $product->image = $path;
    }

    $product->save();

    return response()->json([
        'product_code' => $product->product_code,
        'product_name' => $product->product_name,
        'category_name' => $product->category->category_name ?? '-',
        'unit_name' => $product->unit->unit_name ?? '-',
        'stock' => $product->stock,
        'expiry_date' => $product->expiry_date,
        'image_url' => $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/50x50.png?text=No+Image',
    ]);
}

public function show($id)
    {
        $product = Product::findOrFail($id);

        if(empty($product->image)){
            $product->image = 'https://picsum.photos/400/300?random='.$product->id;
        } else {
            $product->image = asset($product->image);
        }

        return view('admin.customerorders.show', compact('product'));
    }


}
