<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function store(Request $request)
    {
        //falta foto
        $valid = Validator::make($request->only('name', 'email', 'nif', 'password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nif' => 'required | numeric|between:100000000,999999999',
            'password' => 'required|string|min:3',
            'photo' => 'mimes:jpeg,jpg,png,gif|max:10000000'
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        $user = new User();
        $user->fill($request->except(['photo', 'password', 'type', 'active']));

        $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
        $user->photo = $fileName;
        $request->photo->move(public_path('storage/fotos'), $fileName);

        $user->password =  Hash::make($request->password);
        $user->save();
        return response()->json($user, 200);
    }

    public function getMe(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update(Request $request)
    {

        $logged_user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'photo' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'password' => 'string|max:255',
            'nif' => 'numeric|between:100000000,999999999'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //user->fill($request->except(['type','active','email']));
        //user->save();
        $logged_user->fill($request->except(['type', 'active', 'email', 'password']));
        $logged_user->password = Hash::make($request->password);
        $logged_user->save();
        return response()->json(new UserResource($logged_user), 200);
    }
}
