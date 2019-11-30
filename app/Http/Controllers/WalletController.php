<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{

    public function getwalletcount()
    {
        $walletcount = DB::table('wallets')->count();
        return response()->json(['walletcount' => $walletcount], 200);
    }
}
