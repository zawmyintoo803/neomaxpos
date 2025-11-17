<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerTypeController extends Controller
{
    public function index()
    {
        $customerTypes = CustomerType::latest()->paginate(5);
        return view('customer_types.index', compact('customerTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CustomerType::create($validated);
        return response()->json(['success' => true]);
    }

    public function update(Request $request, CustomerType $customerType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $customerType->update($validated);
        return response()->json(['success' => true]);
    }

    public function destroy(CustomerType $customerType)
    {
        $customerType->delete();
        return response()->json(['success' => true]);
    }
    public function exportExcel()
    {
        return Excel::download(new CustomerTypesExport, 'customer_types.xlsx');
    }

    public function exportPDF()
    {
        $types = CustomerType::orderBy('id', 'asc')->get();
        $pdf = Pdf::loadView('customer_types.pdf', compact('types'));
        return $pdf->download('customer_types.pdf');
    }
}
