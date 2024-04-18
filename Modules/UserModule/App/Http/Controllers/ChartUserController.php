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
        $validate = $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
        ]);
    
        $customerId = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();

            $chart = [
                'customer_id' => $customerId,
                'product' => [
                    [
                        'product_id' => $validate['product_id'],
                        'qty' => $validate['qty']
                    ]
                ]
            ];

            DB::connection("mongodb")->collection("chart")->insert($chart);
    
        return response()->json([
            'message' => 'Chart Berhasil Di Tambahkan',
            'data' => $chart,
            'status' => 200
        ]);
    }
    
    public function ShowChart(){
        $customerId = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
    
        $chart = DB::connection("mongodb")->collection("chart")
            ->where('customer_id', $customerId)
            ->first();
    
        if (!$chart || empty($chart['product'])) {
            return response()->json([
                'message' => 'Tidak ada produk dalam chart',
                'status' => 400
            ]);
        }
        $products = [];
        foreach ($chart['product'] as $productData) {
            $productInfo = DB::connection("mongodb")->collection("products")
                ->where('_id', $productData['product_id'])
                ->first();
    
            if ($productInfo) {
                $productInfo['qty'] = $productData['qty'];
                $products[] = $productInfo;
            }
        }
        $chart['product'] = $products;
        // subtotal chart
        $chart['subtotal'] = 0;
        foreach ($products as $product) {
            $product['harga_diskon'] = str_replace(".", "", $product['harga_diskon']);
            $chart['subtotal'] += $product['harga_diskon'] * $product['qty'];

        }
        $chart['subtotal'] = number_format($chart['subtotal'], 0, ',', '.');
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
