<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Modules\UserModule\App\Models\Customer;

class UserModuleController extends Controller
{
    public function show()
    {

        $customer = Customer::get()->where('user_id', auth()->user()->id)->first();
        if($customer){
            return response()->json([
                'success' => true,
                'data' => $customer
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }

    }

    public function update(Request $request)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        if ($customer) {
            if (!empty($request->file('photo'))) { 
             // Hapus foto lama jika ada
             if ($customer->photo) {
                $fileNameToDelete = basename($customer->photo);               
                Storage::disk('public')->delete('clientphoto/' . $fileNameToDelete);
            }
                $file = $request->file('photo');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->storeAs('clientphoto', $fileName, 'public');
                $fileUrl = url('storage/clientphoto/' . $fileName);
    
             
                if ($file->getSize() > 6048) {
                    return response()->json([
                        "message" => "File terlalu besar",
                        "status" => 400,
                    ]);
                }
    
                
    
          
                $customer->update([
                    'phone_number' => $request->phone_number,
                    'nama_lengkap' => $request->nama_lengkap,
                    'photo' => $fileUrl
                ]);
            } else {
               
                $customer->update([
                    'phone_number' => $request->phone_number,
                    'nama_lengkap' => $request->nama_lengkap,
                ]);
            }
    
            // Response berhasil
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $customer
            ], 200);
        } else {
          
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => ''
            ], 404);
        }
    }
    
    public function updateaccount(Request $request){
        $validate = $request->validate([

            'email' => 'required|email',
            'password' => 'required',
        ]);
        $customer = User::where('id', auth()->user()->id)->first();
        if ($customer) {
            $customer->update([
                'email' => $validate['email'],
                'password' => bcrypt($validate['password']),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $customer
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }
    
}
