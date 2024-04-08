<?php

namespace Modules\SellerModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\SellerModule\App\Models\Seller;

class SellerModuleController extends Controller
{
    public function getSellerInfobyId($id)
    {
        $data = Seller::where('id', $id)->first();
        if ($data) {
            return response()->json([
                'message' => 'success',
                'data' => $data,
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'data not found',
                'status' => 404,
            ]);
        }
    }

    public function ShowSeller()
    {
        $data = Auth::user();
        return response()->json($data);
    }
    public function UpdateDataSeller(Request $request)
    {
        try {
            $request->validate([
                'nama_toko' => 'required',
                'nama_pemilik' => 'required',
                'no_hp' => 'required',
                'deskripsi' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'alamat' => 'required',
                'foto' => 'required',
            ]);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }
        $file = $request->file('foto');
        // split space to _
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        // upload fil ke storage folder sellerfoto
        $file->storeAs('sellerfoto', $fileName, 'public');
        $fileUrl = url('storage/sellerfoto/' . $fileName);
       if($file->getSize() > 5048){
            return response()->json([
                "message" => "File terlalu besar",
                "status"     => 400,
            ]);
        }
        // notificarion to mail usingmailing
      
        $seller = User::where('user_id', Auth::user()->id)->first()
            ->update([
                'nama_toko' => $request->nama_toko,
                'nama_pemilik' => $request->nama_pemilik,
                'no_hp' => $request->no_hp,
                'deskripsi' => $request->deskripsi,
                'kota' => $request->kota,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'alamat' => $request->alamat,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'foto' => $fileUrl,
            ]);
        return response()->json([
            "message" => "success",
            "data" => $seller,
            "status" => 200
        ]);
    }
}
