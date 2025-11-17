<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    // Show user list
    public function index()
    {
        $units = Unit::all();       
        return view('units.index', compact('units'));
    }

    // Store (Add new user)
    public function storeAjax(Request $request)
    {
        logger($request->all());
        $validator = Validator::make($request->all(), [
            'unit_name'     => 'required|string|max:100',    
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $unit = Unit::create([
            'unit_name'  => $request->query('unit_name'),           
        ]);        

        return response()->json([
            'message' => 'Unit added successfully',
            'unit'    => $unit
        ]);
    }

    // Update (Edit existing user)
    public function updateAjax(Request $request, Unit $user)
    {
        // Validate but allow the same email for the same user
        $validator = Validator::make($request->all(), [
            'unit_name'     => 'required|string|max:100',           
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->unit_name = $request->query('unit_name');
        $user->save();
    

        return response()->json([
            'message' => 'Unit updated successfully',
            'unit'    => $user
        ]);
    }

    // Delete user
    public function destroyAjax(Unit $unit)
    {
        logger($unit);
        $unit->delete();
        return response()->json(['message' => 'Unit deleted successfully', 'id' => $unit->id]);
    }
}
