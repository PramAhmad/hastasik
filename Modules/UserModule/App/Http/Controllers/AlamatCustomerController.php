<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\UserModule\App\Models\AlamatCustomer;

class AlamatCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alamat = AlamatCustomer::get()->where('customer_id', auth()->user()->id);
        $count = AlamatCustomer::count();
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
        try{
            $request->validate([
                'nama_penerima' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required',
                'kode_pos' => 'required',
                'kota' => 'required',
                'provinsi' => 'required',
                'customer_id' => 'required'
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
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'customer_id' => $request->customer_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $alamat
        ], 201);
    }

    /**
     * Show the specified resource.
     */
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
        try{
            $request->validate([
                'nama_penerima' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required',
                'kode_pos' => 'required',
                'kota' => 'required',
                'provinsi' => 'required',
                'customer_id' => 'required'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'data' => ''
            ], 400);
        }

        $alamat = AlamatCustomer::find($id);
        $alamat->update([
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'customer_id' => $request->customer_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diupdate',
            'data' => $alamat
        ], 200);
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
}
