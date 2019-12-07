<?php

namespace App\Http\Controllers;

use App\User;
use App\Wallet;
use App\Movement;
use App\Http\Resources\WalletResource;
use App\Http\Resources\MovementResource;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request){
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }
    //store USer
    public function store(Request $request)
    {
        $valid = Validator::make($request->only('name', 'email', 'nif', 'password'), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'nif' => 'required | numeric|between:100000000,999999999',
            'password' => 'required|string|min:3|max:16',
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        $user = new User();
        $user->fill($request->except(['photo', 'password', 'type', 'active']));

        if ($request->hasFile('photo')) {
            $validatePhoto = Validator::make($request->only('photo'), [
                'photo' => 'image'
            ]);
            if ($validatePhoto->fails()) {
                return response()->json(['message' => $validatePhoto->errors()->all()], 400);
            }
            $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
            $user->photo = $fileName;
            $request->photo->move(public_path('storage/fotos'), $fileName);
        } else {
            $user->photo = 'default.png';
        }

        $user->password =  Hash::make($request->password);
        $user->type = 'u';
        $user->save();

        $wallet = new Wallet;
        $wallet->id = $user->id;
        $wallet->email = $user->email;
        $wallet->balance = 0;
        $wallet->save();

        return response()->json($user, 200);
    }
    public function storeOperator(Request $request)
    {
        $valid = Validator::make($request->only('name', 'email', 'nif', 'password'), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|max:16',
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        $user = new User();
        $user->fill($request->only('name', 'email'));

        if ($request->hasFile('photo')) {
            $validatePhoto = Validator::make($request->only('photo'), [
                'photo' => 'image'
            ]);
            if ($validatePhoto->fails()) {
                return response()->json(['message' => $validatePhoto->errors()->all()], 400);
            }
            $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
            $user->photo = $fileName;
            $request->photo->move(public_path('storage/fotos'), $fileName);
        } else {
            $user->photo = 'default.png';
        }

        $user->password =  Hash::make($request->password);
        $user->type = 'o';
        $user->save();
        return response()->json($user, 200);
    }
    public function storeAdministrator(Request $request)
    {
        $valid = Validator::make($request->only('name', 'email', 'nif', 'password'), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|max:16',
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        $user = new User();
        $user->fill($request->only('name', 'email'));

        if ($request->hasFile('photo')) {
            $validatePhoto = Validator::make($request->only('photo'), [
                'photo' => 'image'
            ]);
            if ($validatePhoto->fails()) {
                return response()->json(['message' => $validatePhoto->errors()->all()], 400);
            }
            $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
            $user->photo = $fileName;
            $request->photo->move(public_path('storage/fotos'), $fileName);
        } else {
            $user->photo = 'default.png';
        }

        $user->password =  Hash::make($request->password);
        $user->type = 'a';
        $user->save();
        return response()->json($user, 200);
    }
    public function getMe(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update(Request $request)
    {

        //return response()->json($request, 400);
        $user = $request->user();
        //limpar empty fields
        array_filter($request->all());


        $validator = Validator::make($request->only('name', 'nif', 'password', 'passwordConfirmation', 'currentPassword'), [
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:3|max:16',
            'passwordConfirmation' => 'nullable|string|min:3|max:16|same:password',
            'currentPassword'  => 'nullable|string|min:3|max:16',
            'nif' => 'nullable|numeric|between:100000000,999999999'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()], 400);
        }

        $user->fill($request->except(['type', 'active', 'email', 'password', 'photo']));

        if (!is_null($request->password)) {
            //caso a current password nao seja igual a do user nao alterar
            if (!Hash::check($request->currentPassword, $user->password)) {
                return response()->json(
                    ['message' => 'Current Password! Wrong'],
                    400
                );
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $validatePhoto = Validator::make($request->only('photo'), [
                'photo' => 'image'
            ]);
            if ($validatePhoto->fails()) {
                return response()->json(['message' => $validatePhoto->errors()->all()], 400);
            }
            $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
            $user->photo = $fileName;
            $request->photo->move(public_path('storage/fotos'), $fileName);
        }

        $user->save();
        return response()->json($user, 200);
    }

    public function getWallet(Request $request)
    {
        $wallet = $request->user()->wallet;
        return response()->json($wallet, 200);
    }

    public function getMovements(Request $request)
    {
        //add ->whereNotNull('transfer_wallet_id') para testar como trasnferwallet
        $movements = Movement::where('wallet_id', $request->user()->wallet->id)->orWhere('transfer_wallet_id', $request->user()->wallet->id);
        $movements = $movements->orderBy('date', 'desc')->paginate(10);
        return MovementResource::collection($movements);
    }

    public function destroy(Request $request,$id)
    {
        $user = User::findOrFail($id);

        if($request->user()->id == $id){
            return response()->json(['message' => "cant delete yourself"], 400);
        }
        if($user->type === "u"){
            return response()->json(['message' => "cant delete users, Only Operators Or Admins"], 400);
        }
        $user->delete();
        return response()->json(null, 204);
    }


    public function DeactivateReactivateUser(Request $request,$id)
    {
        $user = User::findOrFail($id);

        if($user->type !== "u"){
            return response()->json(['message' => "cant deactivate or reactivate Operators Or Admins"], 400);
        }

        $user->active = !$user->active;
        $user->save();
        return response()->json($user, 200);
    }

}
