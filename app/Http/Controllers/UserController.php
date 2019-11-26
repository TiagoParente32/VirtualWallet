<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

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
        
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        //user->fill($request->except(['type','active','email']));
        //user->save();
        $logged_user->fill($request->except(['type','active','email']));        
        $logged_user->save();
        return response()->json(new UserResource($logged_user), 200);
    }

    public function getauthuser(Request $request){
        $logged_user = $request->user();
        return response()->json(new UserResource($logged_user), 200);
    }
}
