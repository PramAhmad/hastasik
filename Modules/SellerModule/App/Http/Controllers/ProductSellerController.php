<?php

namespace Modules\SellerModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::connection('mongodb')->collection('products')->where('seller_id', Auth::user()->id)->get();
        if ($data->count() >= 0) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200
            ]);
        } else {
            return response()->json(["message" => "error", "data" => "data not found", "status" => 404]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sellermodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'nama_produk' => 'required',
                'harga' => 'required',
                'deskripsi' => 'required',
                'stok' => 'required',
                'foto' => 'required',
                'category' => 'required'
            ]);
        }catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }

        $data = DB::connection('mongodb')->collection('products')->insert([
            "nama_produk" => $request->nama_produk,
            "seller_id" => Auth::user()->id,
            "harga" => $request->harga,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "foto" => $request->foto,
            "category" => $request->category,
            "created_at" => date('Y-m-d'),
            "updated_at" => date('Y-m-d')
        ]);
        if($data == true){
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200
            
            ]);
        }else{
            return response()->json([
                "message" => "error",
                "data" => "data not found",
                "status" => 404
            
            ]);
        }

        
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('sellermodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('sellermodule::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'nama_produk' => 'required',
                'harga' => 'required',
                'deskripsi' => 'required',
                'stok' => 'required',
                'foto' => 'required',
                'category' => 'required'
            ]);
        }catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }

        $data = DB::connection('mongodb')->collection('products')->where('_id', $id)->update([
            "nama_produk" => $request->nama_produk,
            "seller_id" => Auth::user()->id,
            "harga" => $request->harga,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "foto" => $request->foto,
            "category" => $request->category,
            "updated_at" => date('Y-m-d')
        ]);
        if($data == true){
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200
            
            ]);
        }else{
            return response()->json([
                "message" => "error",
                "data" => "data not found",
                "status" => 404
            
            ]);
        }
    }

    public function destroy($id)
    {
        $data = DB::connection('mongodb')->collection('products')->where('_id', $id)->delete();
        if($data == true){
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200
            
            ]);
        }else{
            return response()->json([
                "message" => "error",
                "data" => "data not found",
                "status" => 404
            
            ]);
        }
    }
}
