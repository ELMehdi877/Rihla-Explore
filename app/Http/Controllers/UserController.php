<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function usersByMonth(){
        $stats = DB::table('users')
        ->select(DB::raw('EXTRACT(YEAR from created_at) as year, EXTRACT(MONTH from created_at) as month, COUNT(*) as total'))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return response()->json($stats);

    }
}
