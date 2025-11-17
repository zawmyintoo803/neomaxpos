<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PurchaseController;

class PurchaseController extends Controller
{
public function index()
{
    $today = Carbon::today()->subDay(3);

    $purchases = Purchase::with(['supplier', 'paymentMethod'])
                 ->latest()
                 ->paginate(10);

    $suppliers = Supplier::orderBy('name')->get();
    $paymentMethods = PaymentMethod::orderBy('payment_name')->get();

    // Today’s invoices
    $todayInvoices = Purchase::whereDate('created_at', $today)
                         ->orderBy('invoice_no')
                         ->get(['invoice_no']);

    $todayTotal = Purchase::whereDate('created_at', $today)
                      ->sum('grand_total');

    return view('admin.purchases.index', compact(
        'purchases',
        'suppliers',
        'paymentMethods',
        'todayInvoices',
        'todayTotal'
    ));
}

// AJAX fetch
public function ajax(Request $request)
{
    $query = Purchase::with(['customer', 'paymentMethod']);

    if ($request->customer_id) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->payment_method_id) {
        $query->where('payment_method_id', $request->payment_method_id);
    }

    if ($request->invoice_no) {
        $query->where('invoice_no', $request->invoice_no);
    }

    $purchases = $query->latest()->paginate(10);

    // Get today’s invoices for the dropdown
    $todayInvoices = Purchase::whereDate('created_at', Carbon::today())
                         ->orderBy('invoice_no')
                         ->get(['invoice_no']);

    $filteredTotal = $purchases->sum('total_amount');

    // Return JSON
    return response()->json([
        'table' => view('caisher.purchases.partials.purchases_table', compact('purchases'))->render(),
        'pagination' => $purchases->withQueryString()->links()->render(),
        'filteredTotal' => number_format($filteredTotal,2),
        'todayInvoices' => $todayInvoices
    ]);
}


public function store1(Request $request)
{
    $request->validate([
        'customer_id' => 'nullable|exists:suppliers,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'paid_amount' => 'required|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.id' => 'required|exists:products,id',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.discount' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // Create Purchase
        $sale = Purchase::create([
            'customer_id' => $request->customer_id,
            'payment_method_id' => $request->payment_method_id,
            'total_amount' => 0, // will update after items
            'paid_amount' => $request->paid_amount,
        ]);

        $totalAmount = 0;

        // Create Purchase Items
        foreach ($request->items as $item) {
            $lineTotal = ($item['price'] * $item['qty']) - ($item['discount'] ?? 0);
            $totalAmount += $lineTotal;

            PurchaseItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'discount' => $item['discount'] ?? 0,
                'total' => $lineTotal,
            ]);
        }

        // Update Purchase total
        $sale->total_amount = $totalAmount;
        $sale->save();

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Purchase saved successfully.',
            'sale_id' => $sale->id
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save sale. '.$e->getMessage()
        ], 500);
    }
}
public function create()
    {
        $products = Product::all();
        $paymentMethods = \App\Models\PaymentMethod::all();
        $suppliers = Supplier::all();
        return view('admin.purchases.create', compact('products', 'paymentMethods','suppliers'));
    }

    /**
     * Fetch product by code for POS
     */
    public function fetchProductByCode(Request $request)
    {
        $code = $request->product_code;

        if (!$code) {
            return response()->json(['error'=>'No product code provided'],400);
        }

        $product = Product::select('id','product_code','product_name','price','stock')
            ->where('product_code',$code)->first();

        if (!$product) {
            return response()->json(['error'=>'Product code not found'],404);
        }

        return response()->json($product);
    }

    /**
     * Optional: Fetch all products for POS page load
     */
    public function getAllProducts()
    {
        $products = Product::select('id','product_code','product_name','price')->get();
        return response()->json($products);
    }
    public function store(Request $request)
    {
        logger($request->all());
        $request->validate([
            'products' => 'required|array|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'additional_discount' => 'nullable|numeric|min:0',
        ]);

        $productsInput = $request->input('products');
        $additionalDiscount = $request->input('additional_discount', 0);
        $paidAmount = $request->input('paid_amount', 0);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($productsInput as $p) {
                $product = Product::findOrFail($p['id']);

                if ($p['qty'] > $product->stock) {
                    return back()->with('error', "Quantity for {$product->product_name} exceeds stock.");
                }

                $subtotal += $product->price * $p['qty'];
                $totalDiscount += $p['discount'] ?? 0;
            }

            $taxable = $subtotal - $totalDiscount - $additionalDiscount;
            $tax = $taxable * 0.05;
            $grandTotal = $taxable + $tax;

            // Generate invoice number (example: INV-YYYYMMDD-XXX)
            $today = Carbon::today()->format('Ymd');
            $lastInvoice = Purchase::whereDate('created_at', Carbon::today())
                ->orderBy('id','desc')
                ->first();
            $nextNumber = $lastInvoice ? ((int)substr($lastInvoice->invoice_no, -3) + 1) : 1;
            $invoiceNo = 'INV-'.$today.'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create Purchase
            $purchases = Purchase::create([
                'invoice_no' => $invoiceNo,
                'purchase_date' => now(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $additionalDiscount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'payment_method_id' => $request->payment_method_id,
                'user_id' => auth()->id(),
                'supplier_id' => $request->supplier_id ?? null,
            ]);

            // Create Purchase Items
            foreach ($productsInput as $p) {
                $product = Product::find($p['id']);
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'qty' => $p['qty'],
                    'price' => $product->price,
                    'discount' => $p['discount'] ?? 0,
                    'total' => ($product->price * $p['qty']) - ($p['discount'] ?? 0),
                ]);

                // Reduce stock
                $product->decrement('stock', $p['qty']);
            }

            DB::commit();

            return redirect()->route('purchases.create')->with('success', 'Purchase completed successfully! Invoice: '.$invoiceNo);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }
}