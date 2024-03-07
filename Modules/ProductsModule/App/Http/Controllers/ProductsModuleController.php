<?php

namespace Modules\ProductsModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Htt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\ProductsModule\App\Models\Products;

class ProductsModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::connection('mongodb')->collection('products')->get();
        
        return response()->json(["message" => "success","data" => $data,"status" => 200]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productsmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->guard('seller')->check()) {
            $data = DB::connection('mongodb')->collection('products')->insert([

                "nama_product" => "pramuditaa",
                "seller_id" => "1",
                "harga" => "100000",
                "deskripsi" => "ini adalah deskripsi",
                "stok" => "10",
                "berat" => "1",
                "foto" => ["foto1","foto2"],
                "category" => ["category1","category2"],
                "status" => "1",
                "created_at" => "2021-10-10",
                "updated_at" => "2021-10-10"
    
            ]);
    
            return response()->json(["message" => "success","data" => $data,"status" => 200]);
        } else {
            return response()->json(["message" => "unauthorized","status" => 401]);
        }
    
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('productsmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('productsmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
