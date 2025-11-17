<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchases;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(){
        $purchases = Purchases::with(['supplier','product'])->orderBy('purchase_date','desc')->get();
        return view('purchases.index',compact('purchases'));
    }

    public function create(){
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create',compact('suppliers','products'));
    }

    public function store(Request $request){
        $request->validate([
            'invoice_no'=>'required|unique:purchases',
            'supplier_id'=>'required|exists:suppliers,id',
            'product_id'=>'required|exists:products,id',
            'purchase_date'=>'required|date',
            'quantity'=>'required|integer|min:1',
            'unit_price'=>'required|numeric|min:0',
        ]);
        $total_amount = $request->quantity * $request->unit_price;
        Purchases::create(array_merge($request->all(),['total_amount'=>$total_amount]));
        return redirect()->route('purchases.index')->with('success','Purchase added successfully!');
    }

    public function exportExcel(){
        return Excel::download(new PurchasesExport,'purchases.xlsx');
    }

    public function exportPDF(){
        $purchases = Purchases::with(['supplier','product'])->get();
        $pdf = Pdf::loadView('purchases.pdf',compact('purchases'));
        return $pdf->download('purchases.pdf');
    }
}
