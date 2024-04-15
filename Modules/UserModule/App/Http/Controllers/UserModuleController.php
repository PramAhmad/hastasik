<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        
        $validate = $request->validate([
         
            'phone_number' => 'required',
            'nama_lengkap' => 'required',
            'photo' => 'required'
        ]);

        $customer = Customer::where('user_id', auth()->user()->id)->first();
        if($customer){
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
            $customer->update([
         
                'phone_number' => $validate['phone_number'],
                'nama_lengkap' => $validate['nama_lengkap'],
                'photo' => $fileUrl
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
