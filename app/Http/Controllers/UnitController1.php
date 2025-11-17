<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index() {
        return view('admin.units.index');
    }

    public function apiIndex() {
        return Unit::orderBy('id','desc')->get();
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|unique:units,unit_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()], 422);
        }

        $unit = Unit::create(['unit_name' => $request->unit_name]);

        return response()->json($unit, 201);
    }

    public function update(Request $request, $id) {
        $unit = Unit::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|unique:units,unit_name,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()->first()], 422);
        }

        $unit->unit_name = $request->unit_name;
        $unit->save();

        return response()->json($unit);
    }

    public function destroy($id) {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['message'=>'Unit deleted']);
    }
}
