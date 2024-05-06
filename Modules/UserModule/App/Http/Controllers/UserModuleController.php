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
        $request->validate([
            'nama_lengkap' => 'required',
            'phone_number' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:6048',
        ]);
    
        $customer = Customer::where('user_id', auth()->user()->id)->first();
    
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    
        if ($request->hasFile('photo')) {
            $this->deletePhoto($customer->photo); 
            $photoUrl = $this->storePhoto($request->file('photo')); 
        } else {
            $photoUrl = $customer->photo; 
        }
    
        $customer->update([
            'nama_lengkap' => $request->nama_lengkap,
            'phone_number' => $request->phone_number,
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
    
    public function updatepassword(Request $request){
        $request->validate([
            'password' => 'required',
            'new_password' => 'required',
        ]);
    
        $user = User::find(auth()->user()->id);
    
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama salah',
            ], 400);
        }
    
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui',
        ], 200);
    }

    public function ForgotPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar',
            ], 404);
        }
    
       //kirim notifikasi untuk redirect ke halaman reset password
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Email verifikasi telah dikirim',
        ], 200);
    }

    public function ResetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar',
            ], 404);
        }
    
        $user->update([
            'password' => bcrypt($request->password),
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset',
        ], 200);
    }
    
}
