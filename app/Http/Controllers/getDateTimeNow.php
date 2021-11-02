<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class getDateTimeNow extends Controller
{
    public function getNow() {
        $now = Carbon::now()->toDateTimeString();
        return response()->json(['now' => $now], 200);
    }
}
