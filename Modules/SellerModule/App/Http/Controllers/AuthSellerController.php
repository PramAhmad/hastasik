<?php

namespace Modules\SellerModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SellerRegisterNotification;
use AWS\CRT\Log;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use BackblazeB2\Client as B2Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\SellerModule\App\Models\Seller;

class AuthSellerController extends Controller
{
    public function RegisterSeller(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'nama_toko' => 'required',
            'nama_pemilik' => 'required',
            'no_hp' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'alamat' => 'required',
           
        ]);
    
        $file = $request->file('foto');
        // split space to _ 
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        // upload fil ke storage folder sellerfoto
        $file->storeAs('sellerfoto', $fileName, 'public');
        $fileUrl = url('storage/sellerfoto/' . $fileName);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'seller',
        ]);
    
        $seller = Seller::create([
            'user_id' => $user->id,
            'nama_toko' => $request->nama_toko,
            'nama_pemilik' => $request->nama_pemilik,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'foto' => $fileUrl,
            'deskripsi' => $request->deskripsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'alamat' => $request->alamat,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'status' => 0,
        ]);
        // validate gagal create seller
        if (!$seller) {
            return response()->json([
                "message" => "Gagal membuat seller",
                "status" => 400,
            ]);
        }   
        // Realtime notification to admin in web
        return response()->json([
            "message" => "Tunggu Konfirmasi dari admin",
            "data" => $seller,
            "status" => 200,
        ]);
    }
    

    public function getAddress() {
        // using example kooridnate
        $latitude = -6.1753924;
        $longitude = 106.8271528;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=";
        $response = file_get_contents($url);
        $response = json_decode($response);
        $address = $response->results[0]->formatted_address;
        return $address;
    }

        public function LoginSeller(Request  $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // cek email dan password ada di table user tidak
        if(Auth::attempt($request->only('email', 'password'))){
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    "message" => "Email atau password salah",
                    "status" => 401,
                ]);
            }
            // cek status seller
                $seller = Seller::where('user_id', $user->id)->first();
                if ($seller->status == 0) {
                    return response()->json([
                        "message" => "Seller belum di konfirmasi",
                        "status" => 401,
                    ]);
                }
                
                
        
            // login seller
            $token = $user->createToken('seller')->plainTextToken;
            return response()->json([
                "message" => "success",
                "data" => $user,
                "token" => $token,
                "status" => 200,
            ]);
        }
        else {
            return response()->json([
                "message" => "error",
                "data" => "unauthorized",
                "status" => 401,
            ]);
        }
    
        }



        
}
