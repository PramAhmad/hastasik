<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\UserModule\App\Models\Client;

class AuthUserController extends Controller
{
    public function RegisterUser(Request $request)
    {
       try {
            $validate = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'province' => 'required',
                'postal_code' => 'required',
                'photo' => 'required'
            ]);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }
        $file = $request->file('photo');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->storeAs('clientphoto', $fileName, 'public');
        $fileUrl = url('storage/clientphoto/' . $fileName);
        if ($file->getSize() > 6048) {
            return response()->json([
                "message" => "File terlalu besar",
                "status"     => 400,
            ]);
        }

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => bcrypt($request->password),
            'role'=>'customer'
          
        ]);

        $customer = Client::create([
            'user_id' => $user->id,
            'phone_number' => $validate['phone'],
            'address' => $validate['address'],
            'city' => $validate['city'],
            'province' => $validate['province'],
            'postal_code' => $validate['postal_code'],
            'photo' => $fileUrl
        ]);
     
        if(!$user || !$customer){
            return response()->json([
                'message' => 'Register Failed'

            ], 400);
        }
        // confirm mail
        return response()->json([
            'message' => 'Register Success',
            'data' => $user
        ], 200);

    }

    public function LoginUser(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt($request->only('email', 'password'))){
            $user = User::where('email', $validate['email'])->first();
            $token = $user->createToken('token')->plainTextToken;
            if(!$user || !Hash::check($validate['password'], $user->password)){
                return response()->json([
                    'message' => 'Login Failed'
                ], 400);
            }
        }
        if($user->role != 'customer'){
            return response()->json([
                'message' => 'Login Failed'
            ], 400);
        }
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'message' => 'Login Success',
            'data' => $user,
            'token' => $token
        ], 200);
    
    }
}   
