<?php

namespace App\Http\Controllers;

use App\Models\Township;
use Illuminate\Http\Request;

class TownshipController extends Controller
{
    public function getTownships($division_id)
    {
        $townships = Township::where('division_id', $division_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($townships);
    }

    public function getByDivision($division_id)
    {
        $townships = Township::where('division_id', $division_id)->get();
        return response()->json($townships);
    }
}
