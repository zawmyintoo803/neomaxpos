<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Supplier;
use App\Models\Township;
use App\Models\SupplierType;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::with(['supplierType', 'division', 'township'])
            ->latest()
            ->paginate(10);

        $supplierTypes = SupplierType::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        return view('suppliers.index', compact('suppliers', 'supplierTypes', 'divisions'));
    }

    /**
     * Store a newly created supplier (AJAX).
     */
    public function store(Request $request)
    {
        logger($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'supplier_type_id' => 'nullable|integer|exists:supplier_types,id',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'township_id' => 'nullable|integer|exists:townships,id',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json(['message' => 'Supplier added successfully!', 'supplier' => $supplier]);
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        logger($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'supplier_type_id' => 'nullable|integer|exists:supplier_types,id',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'township_id' => 'nullable|integer|exists:townships,id',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($validated);

        return response()->json(['message' => 'Supplier updated successfully!', 'supplier' => $supplier]);
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
         // now works properly

        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted successfully!']);
    }

    /**
     * Get Townships based on Division ID.
     */
    public function getTownships($divisionId)
    {
        $townships = Township::where('division_id', $divisionId)->orderBy('name')->get();

        return response()->json($townships);
    }
}
