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
        $valid = Validator::make($request->only('name', 'email', 'nif', 'password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nif' => 'required|numeric|between:100000000,999999999',
            'password' => 'required|string|min:3'
        ]);
        if ($valid->fails()) {
            return response()->json(['message' => $valid->errors()->all()], 400);
        }
        $user = new User();
        $user->fill($request->except(['photo', 'password', 'type', 'active']));


        if ($request->hasFile('photo')) {
            $fileName = time() . '.' . $request->photo->getClientOriginalExtension();
            $user->photo = $fileName;
            $request->photo->move(public_path('storage/fotos'), $fileName);
        } else {
            $user->photo = 'default.png';
        }


        $user->password =  Hash::make($request->password);
        $user->save();
        return response()->json($user, 200);
    }


    // public function addImageToStorage($filename, $image)
    // {
    //     $foldername = 'public/storage/fotos';
    //     $exists = Storage::disk('local')->exists($foldername . $filename);
    //     if ($exists) {
    //         Storage::disk('local')->delete($foldername . $filename);
    //     }
    //     //copia para o novo file
    //     Storage::disk('local')->put($foldername . $filename, File::get($image));
    // }

    public function getMe(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update(Request $request)
    {

        $logged_user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'photo' => '',
            'password' => 'string|max:255',
            'nif' => ''
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        //user->fill($request->except(['type','active','email']));
        //user->save();
        $logged_user->fill($request->except(['type', 'active', 'email']));
        $logged_user->save();
        return response()->json(new UserResource($logged_user), 200);
    }



    public function getauthuser(Request $request)
    {
        $logged_user = $request->user();
        return response()->json(new UserResource($logged_user), 200);
    }
}
