<?php

namespace App\Http\Controllers;

use App\Wallet;
use App\Movement;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MovementController extends Controller
{
    public function getWalletMovements(Request $request)
    {
        $wallet_id = DB::table('wallets')->where('email', '=', $request->user()->email)->pluck('id');
        $movements = DB::table('movements')->where('wallet_id', '=', $wallet_id)->get();
        return response()->json($movements, 200);
    }

    public function store(Request $request)
    {
        if ($request->user()->type == "o") {
            return $this->storeAsOperator($request);
        } else if ($request->user()->type == "u") {
            return $this->storeAsUser($request);
        } else {
            return;
        }
    }

    public function storeAsOperator(Request $request)
    {
        $movement = new Movement;
        $valid = Validator::make($request->only('email', 'value', 'type_payment', 'source_description'), [
            'email' => 'required|string|email|max:255',
            'value' => 'required | numeric|between:0.01,5000.00',
            'type_payment' => 'required | in:c,bt',
            'source_description' => 'required | string | max:500',
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }

        //If payment type is cash
        $movement->fill($request->except(['type', 'transfer', 'transfer_movement_id', 'mb_entity_code', 'mb_payment_reference', 'description', 'updated_at', 'created_at', 'iban']));

        //receiver wallet
        $receiver = Wallet::where('email', '=', $request->email)->get()->first();

        $movement->wallet_id = $receiver->id;
        $movement->type = "i";
        $movement->transfer = 0;

        //falta validar o IBAN corretamente
        if ($request->type_payment == 'bt') {
            $valid = Validator::make($request->only('iban'), [
                'iban' => 'required|regex:/([A-Z]){2}[0-9]{23}/'
            ]);

            if ($valid->fails()) {
                return response()->json(['message' => $valid->errors()->all()], 400);
            }
            $movement->type_payment = "bt";
            $movement->iban = $request->iban;
        } else {
            $movement->type_payment = "c";
        }

        $movement->start_balance = $receiver->balance;
        $movement->end_balance = $movement->start_balance + $request->value;
        $movement->value = $request->value;
        $movement->date = date('Y-m-d H:i:s');

        $receiver->balance = $movement->end_balance;

        $receiver->save();
        $movement->save();
        return response()->json($receiver, 200);
    }

    public function storeAsUser(Request $request)
    {
        //rip.png
    }


    //As a platform user I want to edit any movement (expense or income) of my virtual wallet.
    // When editing a movement, I can only change the category and the description. All the
    // remaining information of the movement is immutable.
    public function update(Request $request, Movement $movement)
    {
        $validator = Validator::make($request->only('description', 'category'), [
            'description' => 'nullable|string|max:255',
            'category' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()], 400);
        }

        $newMovement = Movement::where('id',$movement->id)->firstOrFail();
        $newMovement->fill($request->only(['description']));
        $newMovement->category_id = $request->category;

        $newMovement->save();

        return response()->json($newMovement, 200);
    }
}
