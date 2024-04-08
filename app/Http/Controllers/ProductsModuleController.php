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
}
