<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\UserModule\App\Models\Customer;

class ChartUserController extends Controller
{
    public function PushChart(Request $request){
        $validate = [
            'product_id' => 'required',
            'qty' => 'required',
        ];
        try {
            $validate = $request->validate($validate);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }
        $chart = DB::connection("mongodb")->collection("chart")
        ->where('customer_id', Customer::where('user_id', auth()->user()->id)->pluck("id")->first())
        ->first();
    
    if ($chart) {
        $chart['product'][] = [
            'product_id' => $validate['product_id'],
            'qty' => $validate['qty']
        ];
        DB::connection("mongodb")->collection("chart")
            ->where('customer_id', Customer::where('user_id', auth()->user()->id)->pluck("id")->first())
            ->update($chart);
    }
    
         else  {
            $chart = [
                'customer_id' => Customer::where('user_id', auth()->user()->id)->pluck("id")->first(),
                'product' => [
                    [
                        'product_id' => $validate['product_id'],
                        'qty' => $validate['qty']
                    ]
                ]
            ];
            DB::connection("mongodb")->collection("chart")->insert($chart);
        }
        return response()->json([
            'message' => 'Chart Berhasil Di Tambahkan',
            'data' => $chart,
            'status' => 200
        ]); 
    }

    public function ShowChart(){
        $chart = DB::connection("mongodb")->collection("chart")
        ->where('customer_id', Customer::where('user_id', auth()->user()->id)->pluck("id")->first())
        ->first();
        // ambil product id jadi product
        $product = [];
        foreach ($chart['product'] as $key => $value) {
            $product[] = DB::connection("mongodb")->collection("products")
                ->where('_id', $value['product_id'])
                ->first();
        }
        $chart['product'] = $product;
        return response()->json([
            'message' => 'Chart Berhasil Di Tampilkan',
            'data' => $chart,
            'status' => 200
        ]);
    }

    public function DeleteChart($id)  {
    //  delete chart id
        DB::connection("mongodb")->collection("chart")
            ->where('_id', $id)
            ->delete();
        return response()->json([
            'message' => 'Chart Berhasil Di Hapus',
            'status' => 200
        ]);
        
    }
}
