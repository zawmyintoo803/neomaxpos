<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Cashier;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SaleController;

class SaleController extends Controller
{
public function index()
{
    $today = Carbon::today()->subDay(3);

    $sales = Sale::with(['customer', 'paymentMethod'])
                 ->latest()
                 ->paginate(10);

    $customers = Customer::orderBy('name')->get();
    $paymentMethods = PaymentMethod::orderBy('payment_name')->get();

    // Today’s invoices
    $todayInvoices = Sale::whereDate('created_at', $today)
                         ->orderBy('invoice_no')
                         ->get(['invoice_no']);

    $todayTotal = Sale::whereDate('created_at', $today)
                      ->sum('grand_total');

    return view('caisher.sales.index', compact(
        'sales',
        'customers',
        'paymentMethods',
        'todayInvoices',
        'todayTotal'
    ));
}
public function sales_listing_admin(){
    $today = Carbon::today()->subDay(3);

    $sales = Sale::with(['customer', 'paymentMethod'])
                 ->latest()
                 ->paginate(10);

    $customers = Customer::orderBy('name')->get();
    $caishers = Cashier::all();
    $paymentMethods = PaymentMethod::orderBy('payment_name')->get();

    // Today’s invoices
    $todayInvoices = Sale::whereDate('created_at', $today)
                         ->orderBy('invoice_no')
                         ->get(['invoice_no']);

    $todayTotal = Sale::whereDate('created_at', $today)
                      ->sum('grand_total');

    return view('admin.sales_listing.index', compact(
        'sales',
        'customers',
        'paymentMethods',
        'todayInvoices',
        'todayTotal',
        'caishers'
    ));
}
// AJAX fetch
public function ajax(Request $request)
{
    $query = Sale::with(['customer', 'paymentMethod']);

    if ($request->customer_id) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->payment_method_id) {
        $query->where('payment_method_id', $request->payment_method_id);
    }

    if ($request->invoice_no) {
        $query->where('invoice_no', $request->invoice_no);
    }

    $sales = $query->latest()->paginate(10);

    // Get today’s invoices for the dropdown
    $todayInvoices = Sale::whereDate('created_at', Carbon::today())
                         ->orderBy('invoice_no')
                         ->get(['invoice_no']);

    $filteredTotal = $sales->sum('total_amount');

    // Return JSON
    return response()->json([
        'table' => view('caisher.sales.partials.sales_table', compact('sales'))->render(),
        'pagination' => $sales->withQueryString()->links()->render(),
        'filteredTotal' => number_format($filteredTotal,2),
        'todayInvoices' => $todayInvoices
    ]);
}


public function store1(Request $request)
{
    $request->validate([
        'customer_id' => 'nullable|exists:customers,id',
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
        // Create Sale
        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'payment_method_id' => $request->payment_method_id,
            'total_amount' => 0, // will update after items
            'paid_amount' => $request->paid_amount,
        ]);

        $totalAmount = 0;

        // Create Sale Items
        foreach ($request->items as $item) {
            $lineTotal = ($item['price'] * $item['qty']) - ($item['discount'] ?? 0);
            $totalAmount += $lineTotal;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'discount' => $item['discount'] ?? 0,
                'total' => $lineTotal,
            ]);
        }

        // Update Sale total
        $sale->total_amount = $totalAmount;
        $sale->save();

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Sale saved successfully.',
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
        $customers = Customer::all();
        return view('caisher.sales.create', compact('products', 'paymentMethods','customers'));
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
            $lastInvoice = Sale::whereDate('created_at', Carbon::today())
                ->orderBy('id','desc')
                ->first();
            $nextNumber = $lastInvoice ? ((int)substr($lastInvoice->invoice_no, -3) + 1) : 1;
            $invoiceNo = 'INV-'.$today.'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create Sale
            $sale = Sale::create([
                'invoice_no' => $invoiceNo,
                'sale_date' => now(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $additionalDiscount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'payment_method_id' => $request->payment_method_id,
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id ?? null,
            ]);

            // Create Sale Items
            foreach ($productsInput as $p) {
                $product = Product::find($p['id']);
                SaleItem::create([
                    'sale_id' => $sale->id,
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

            return redirect()->route('sales.create')->with('success', 'Sale completed successfully! Invoice: '.$invoiceNo);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }
}