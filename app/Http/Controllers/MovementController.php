<?php

namespace App\Http\Controllers;

use App\User;
use App\Wallet;
use App\Category;

use App\Http\Resources\MovementResource;
use App\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MovementController extends Controller
{
    public function getWalletMovements(Request $request)
    {
        $wallet_id = DB::table('wallets')->where('email', '=', $request->user()->email)->pluck('id');
        $movements = DB::table('movements')->where('wallet_id', '=', $wallet_id)->get();
        return response()->json($movements, 200);
    }

    public function filterWalletMovements(Request $request)
    {
        $movements = Movement::query();
        if ($request->has('id') && $request->id !== null){
            $movements = $movements->where('id','=', $request->id);
        }
        if ($request->has('type') && $request->type !== null){
            $movements = $movements->where('type','=', $request->type);
        }
        if ($request->has('category_id') && $request->category_id !== null){
            $movements = $movements->where('category_id','=', $request->category_id);
        }
        if ($request->has('type_payment') && $request->type_payment !== null){
            $movements = $movements->where('type_payment','=', $request->type_payment);
        }
        if ($request->has('transfer') && $request->transfer !== null){
            $movements = $movements->where('transfer','=', $request->transfer);
            if ($request->has('transfer_email') && $request->transfer_email !== null){
                $wallet = Wallet::where('email','like','%'.$request->transfer_email.'%')->get();
                $movements = $movements->where(function($query) use($wallet){
                    $query->where('transfer_wallet_id','=', $wallet[0]->id)->orWhere('wallet_id','=', $wallet[0]->id)->get();
                });
            }
        }
        if ($request->dataMax < $request->dataMin) {
            return;
        }
        if ($request->has('dataMax') && $request->dataMax !== null){ //Postman: usar as datas entre aspas
            $movements = $movements->where('date', '<=', $request->dataMax);
        }
        if ($request->has('dataMin') && $request->dataMin !== null){
            $movements = $movements->where('date', '>=', $request->dataMin);
        }
        $movements->where(function($query) use($request){
            $query->where('wallet_id', $request->user()->wallet->id)->orWhere('transfer_wallet_id', $request->user()->wallet->id);
        });
        $movements = $movements->orderBy('date', 'desc')->paginate(10);
        return MovementResource::collection($movements);
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
        $expense_movement = new Movement;
        $income_movement = new Movement;

        $valid = Validator::make($request->only('transfer'), [
            'transfer' => 'required|in:0,1'
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        if ($request->transfer) {
            $valid = Validator::make($request->only('email', 'source_description'), [
                'email' => 'required|string|email|max:255',
                'source_description' => 'required | string | max:500'
            ]);
            if ($valid->fails()) {
                return response()->json(['message' => $valid->errors()->all()], 400);
            }
            $expense_movement->source_description = $request->source_description;
        } elseif (!$request->transfer) { //exclusive for payment to an external entity
            $valid = Validator::make($request->only('transfer', 'type_payment'), [
                'transfer' => 'required|in:0,1',
                'type_payment' => 'required | in:mb,bt',
            ]);
            if ($valid->fails()) {
                return response()->json(['message' => $valid->errors()->all()], 400);
            }
            if ($request->type_payment == 'bt') {
                $valid = Validator::make($request->only('iban'), [
                    'iban' => 'required|regex:/([A-Z]){2}[0-9]{23}/'
                ]);
                if ($valid->fails()) {
                    return response()->json(['message' => $valid->errors()->all()], 400);
                }
            } elseif ($request->type_payment == 'mb') {
                $valid = Validator::make($request->only('mb_entity_code', 'mb_payment_reference'), [
                    'mb_entity_code' => 'required|numeric|digits:5',
                    'mb_payment_reference' => 'required|numeric|digits:9'
                ]);
                if ($valid->fails()) {
                    return response()->json(['message' => $valid->errors()->all()], 400);
                }
            }
            $expense_movement->type_payment = $request->type_payment;
        }
        //For all types of payments
        $valid = Validator::make($request->only('value', 'category_id'), [
            'value' => 'required|numeric|between:0.01,5000.00',
            'category_id' => 'required|numeric|digits_between:1,10'
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }

        //Para transferencias
        $receiver_wallet = Wallet::where('email', '=', $request->email)->get()->first();

        //Para pagamentos por multibanco
        $sender_wallet = $request->user()->wallet;

        $expense_movement->wallet_id = $sender_wallet->id;
        $expense_movement->type = "e";
        $income_movement->type = "i";
        $expense_movement->category_id = $request->category_id;

        $expense_movement->transfer_wallet_id = $receiver_wallet->id;
        $income_movement->transfer_wallet_id = $sender_wallet->id;
        $income_movement->transfer = $expense_movement->transfer = $request->transfer;

        $income_movement->wallet_id = $receiver_wallet->id;
        $income_movement->start_balance = $receiver_wallet->balance;
        $income_movement->end_balance = $receiver_wallet->balance + $request->value;
        $income_movement->value = $expense_movement->value = $request->value;
        $expense_movement->start_balance = $request->user()->wallet->balance;
        $expense_movement->end_balance = $expense_movement->start_balance - $request->value;
        $receiver_wallet->balance += $expense_movement->value;
        $sender_wallet->balance -= $request->value;
        $expense_movement->date = $income_movement->date = date('Y-m-d H:i:s');

        $sender_wallet->save();
        $expense_movement->save();

        if ($request->transfer) {
            $receiver_wallet->save();
            $income_movement->save();
        }

        //É necessário fazer o save dos dois movimentos porque só assim é possível aceder aos ids de cada movimentos pq só são criados aquando do save()
        $expense_movement->transfer_movement_id = $income_movement->id;
        $income_movement->transfer_movement_id = $expense_movement->id;

        $expense_movement->save();
        if ($request->transfer) {
            $income_movement->save();
        }
        return response()->json([$receiver_wallet, $expense_movement, $sender_wallet, $income_movement], 200);
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

        $newMovement = Movement::where('id', $movement->id)->firstOrFail();
        $newMovement->fill($request->only(['description']));
        $newMovement->category_id = $request->category;

        $newMovement->save();

        return new MovementResource($newMovement);
    }
}
