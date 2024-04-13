<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsModuleController extends Controller
{
    public function index()
    {
        $limit = request()->query('limit');

        $data = DB::connection('mongodb')
            ->collection('products')
            ->paginate(15);
        $orderByParams = request()->query('orderBy');
        if ($orderByParams == 'desc') {
            $data = $data->sortByDesc('created_at');
        } else if ($orderByParams == 'asc') {
            $data = $data->sortBy('created_at');
        }

        if ($limit) {
            $data = $data->take($limit);
        }

        return response()->json([
            'message' => 'success',
            "count" => $data->count(),
            'data' => $data,
            'status' => 200,
        ]);
    }
    // product by category
    public function productByCategory($category)
    {
        $data = DB::connection('mongodb')
            ->collection('products')
            ->where('category', $category)
            ->get();
        return response()->json([
            'message' => 'success',
            'data' => $data,
            'status' => 200,
        ]);
    }
    public function show($id)
{
    $data = DB::connection('mongodb')
        ->collection('products')
        ->where('_id', $id)
        ->first();
        if (!$data) {
            return response()->json([
                'message' => 'error',
                'data' => 'id tidak ditemukan',
                'status' => 404,
            ]);
        }else{
            return response()->json([
                'message' => 'success',
                'data' => $data,
                'status' => 200,
            ]);
        }

    
}
}
