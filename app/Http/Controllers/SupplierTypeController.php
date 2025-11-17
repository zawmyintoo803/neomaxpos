<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierTypeController extends Controller
{
     public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(SupplierType::orderBy('id', 'desc')->get());
        }

        return view('admin.suppliertype.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        SupplierType::create($data);
        return response()->json(['message' => 'Created successfully']);
    }

    public function show(SupplierType $supplierType)
    {
        return response()->json($supplierType);
    }

    public function update(Request $request, SupplierType $supplierType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $supplierType->update($data);
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy(SupplierType $supplierType)
    {
        $supplierType->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
