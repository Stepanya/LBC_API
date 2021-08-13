<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CbmController extends Controller
{
    public function computeCbm(Request $request) {

        $cbm = $request->length * $request->width * $request->height / 1000000;
        
        return response()->json($cbm, 200);
    }
}
