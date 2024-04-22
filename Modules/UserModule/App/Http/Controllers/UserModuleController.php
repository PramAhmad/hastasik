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

    public function update($request)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }


        if ($request->hasFile('photo')) {
       
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048',
            ]);
 
            if ($customer->photo) {
                $this->deletePhoto($customer->photo);
            }   
            $photoUrl = $this->storePhoto($request->file('photo'));
        } else {
  
            $photoUrl = $customer->photo;
        }

     
        $customer->update([
            'phone_number' => $request->phone_number,
            'nama_lengkap' => $request->nama_lengkap,
            'photo' => $photoUrl,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $customer,
        ], 200);
    }

    protected function storePhoto($photo)
    {
        $fileName = time() . '_' . $photo->getClientOriginalName();
        $photo->storeAs('clientphoto', $fileName, 'public');
        return url('storage/clientphoto/' . $fileName);
    }

    protected function deletePhoto($photoUrl)
    {
        $fileName = basename($photoUrl);
        Storage::disk('public')->delete('clientphoto/' . $fileName);
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
