<?php

namespace Modules\SellerModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\SellerModule\App\Models\Seller;

class ProductSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $id = auth()->user()->id;

        $data = DB::connection('mongodb')
            ->collection('products')
            ->where('seller_id', $id)
            ->get();
        $orderByParams = request()->query('orderBy');
        if ($orderByParams == 'desc') {
            $data = $data->sortByDesc('created_at');
        } else if ($orderByParams == 'asc') {
            $data = $data->sortBy('created_at');
        }

        $response = [
            'message' => 'success',
            "count" => $data->count(),
            'data' => $data,
            'status' => 200,
        ];

        return response()->json($response);
    }

   
    public function create()
    {
        return view('sellermodule::create');
    }

  
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required',
                'harga' => 'required',
                'deskripsi' => 'required',
                'stok' => 'required',
                'foto.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'diskon' => 'required',
                'category' => 'required',

            ]);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data gk valid", "status" => 400]);
        }

        // Handle photos
        $foto = $request->file('foto');
        $fotoNames = [];
        //    loop semua foto
        foreach ($foto as $index => $f) {
            $fotoName = time() . $index . '.' . $f->extension();
            $f->storeAs('fotoproducts', $fotoName, 'public');
            $fileurl = url('storage/fotoproducts/' . $fotoName);
            $fotoNames[$index] = $fileurl;
        }
        $afterDiskon = $request->harga - ($request->harga * $request->diskon / 100);
        //   ambil id dan nama_toko
        $seller = Seller::where('user_id', auth()->user()->id)->first()->only('id', 'nama_toko','foto');

        $afterDiskon = number_format($afterDiskon, 0, ',', '.');
        $harga = number_format($request->harga, 0, ',', '.');
        $data = DB::connection('mongodb')->collection('products')->insert([
            "nama_produk" => $request->nama_produk,
            "seller" => $seller,
            "harga" => $harga,
            "diskon" => $request->diskon,
            "harga_diskon" => $afterDiskon,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "foto" => $fotoNames,
            "category" => $request->category,
            "created_at" => date('Y-m-d-h-m-s'),

        ]);

        if ($data == true) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200,
            ]);
        } else {
            return response()->json([
                "message" => "error",
                "data" => "data not found",
                "status" => 404,
            ]);
        }
    }

 
    public function show($id)
    {
        $data = DB::connection('mongodb')->collection('products')->where('_id', $id)->get();
        if ($data->count() >= 0) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200

            ]);
        } else {
            return response()->json([
                "message" => "error",
                "data" => "data tidak ditemukan",
                "status" => 404

            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_produk' => 'required',
                'harga' => 'required',
                'deskripsi' => 'required',
                'stok' => 'required',
                'foto' => 'required',
                'category' => 'required'
            ]);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }

        $data = DB::connection('mongodb')->collection('products')->where('_id', $id)->update([
            "nama_produk" => $request->nama_produk,
            "seller_id" => auth()->user()->id,
            "harga" => $request->harga,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "foto" => $request->foto,
            "category" => $request->category,
            "updated_at" => date('Y-m-d')
        ]);

        if ($data == true) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200

            ]);
        } else {
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
        if ($data == true) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200

            ]);
        } else {
            return response()->json([
                "message" => "error",
                "data" => "data not found",
                "status" => 404

            ]);
        }
    }
    public function productBySeller($id)
    {
        $data = DB::connection('mongodb')->collection('products')->where('seller_id', $id)->get();
        if ($data->count() >= 0) {
            return response()->json([
                "message" => "success",
                "data" => $data,
                "status" => 200

            ]);
        } else {
            return response()->json([
                "message" => "error",
                "data" => "data tidak ditemukan",
                "status" => 404

            ]);
        }
    }   
}
