<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator=Validator::make($request->all(),[
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        // insert data ke database  pada table users
        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password) 
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ],201);
        }

        return response()->json([
            'success' => false
        ],409);
    }
}
