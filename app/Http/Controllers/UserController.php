<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{






    public function update(Request $request){
        
        $logged_user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'photo' => '',
            'password' => 'string|max:255'
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $logged_user->fill($request->except(['type','nif','active','email']));        
        $logged_user->save();
        return response()->json(new UserResource($logged_user), 200); //Não passar o userResource

    }

}
