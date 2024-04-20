<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\UserModule\App\Models\AlamatCustomer;
use Modules\UserModule\App\Models\Customer;

class AlamatCustomerController extends Controller
{
    
    
    public function index()
    {
      $id = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
   
        $alamat = AlamatCustomer::get()->where('customer_id',$id);
        $count = $alamat->count();
        if($alamat){
            return response()->json([
                'success' => true,
                'message' => 'List alamat customer',
                'count' => $count,
                'data' => $alamat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }

  
    public function store(Request $request)
    {
       $id = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
      
        try{
            $request->validate([
                'nama_penerima' => 'required',
                'nomor_telepon' => 'required',
                'alamat' => 'required',
                'kode_pos' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required', 
                'kelurahan' => 'required',
                'provinsi' => 'required',
                "province_id" => 'required',
                "city_id" => 'required',
               
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'data' => ''
            ], 400);
        }

        $alamat = AlamatCustomer::create([
            'nama_penerima' => $request->nama_penerima,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'provinsi' => $request->provinsi,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'customer_id' =>  $id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $alamat
        ], 201);
    }


    public function show($id)
    {
        $alamat = AlamatCustomer::find($id);
        if($alamat){
            return response()->json([
                'success' => true,
                'message' => 'Detail alamat customer',
                'data' => $alamat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }

  

    public function update(Request $request, $id)
    {
        $alamat = AlamatCustomer::find($id);
       
        if($alamat){
            $alamat->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diupdate',
                'data' => $alamat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }

  
    public function destroy($id)
    {
        $alamat = AlamatCustomer::find($id);
        if($alamat){
            $alamat->delete();
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus',
                'data' => $alamat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }

    public function setAlamatUtama($id)
    {
        $alamat = AlamatCustomer::find($id);
        $id = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
        if($alamat){
            $alamat->update([
                'is_utama' => 1
            ]);
            AlamatCustomer::where('customer_id', $id)->where('id', '!=', $alamat->id)->update([
                'is_utama' => 0
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Alamat utama berhasil diupdate',
                'data' => $alamat
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gak Ada',
                'data' => ''
            ], 404);
        }
    }

    public function getAlamatUtama()
    {
        $id = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
        $alamat = AlamatCustomer::where('customer_id', $id)->where('is_utama', 1)->first();
        if($alamat){
            return response()->json([
                'success' => true,
                'message' => 'Alamat utama customer',
                'data' => $alamat
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
