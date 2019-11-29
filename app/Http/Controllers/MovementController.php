<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    public function getWalletMovements(Request $request)
    {
        $wallet_id = DB::table('wallets')->where('email','=', $request->user()->email)->pluck('id');
        $movements = DB::table('movements')->where('wallet_id','=', $wallet_id)->get();
        return response()->json($movements, 200);
    }

}
