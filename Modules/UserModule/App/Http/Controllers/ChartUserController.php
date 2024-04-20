<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Modules\UserModule\App\Models\Customer;

class ChartUserController extends Controller
{
    public function PushChart(Request $request){
        $validate = $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
        ]);
    
        $customerId = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
        $product = DB::connection("mongodb")->collection("products")->where('_id', $validate['product_id'])->first();   
        $subtotal = 0;
        // harga diskon di kali qty
        if (is_numeric($product['harga_diskon'])) {
            $harga_diskon = (int) str_replace(".", "", $product['harga_diskon']);
            $subtotal = $harga_diskon * $validate['qty'];
        } else {
            $harga = (int) str_replace(".", "", $product['harga']);
            $subtotal = $harga * $validate['qty'];
        }
        // number format subtotal jadi kayak haga diskon
        $subtotal = number_format($subtotal, 0, ',', '.');
            $chart = [
                'customer_id' => $customerId,
                'product' => $product,
                "qty" => $validate['qty'],
                "subtotal" => $subtotal,
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
        
      
        $charts = DB::connection("mongodb")->collection("chart")
            ->where('customer_id', $customerId)
            ->get();
         
        if ($charts->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada produk dalam chart',
                'status' => 400
            ]);
        }
    
    
 
        $total = 0;
    
        // Iterasi chart
        foreach ($charts as &$chart) {
            $chart = json_decode(json_encode($chart), true);
            $chart['product']['_id'] = $chart['product']['_id']['$oid'];
            $chart["subtotal"] = 0; 
            
            if (is_numeric($chart['product']['harga_diskon'])) {
                $harga_diskon = (int) str_replace(".", "", $chart['product']['harga_diskon']);
                
                // Hitung subtotal
                $chart['tempsubtotal'] = $harga_diskon * $chart['qty'];
                $chart['subtotal'] = number_format($chart['tempsubtotal'], 0, ',', '.');
                
                $total += $chart['tempsubtotal'];
            } else {
                $harga = (int) str_replace(".", "", $chart['product']['harga']);
                
                // Hitung subtotal
                $chart['tempsubtotal'] = $harga * $chart['qty'];
                $chart['subtotal'] = number_format($chart['tempsubtotal'], 3, ',', '.');
                $total += $chart['tempsubtotal'];
                
            }
        }
        
        // map sub total
        $chart["subtotal"] = number_format($chart["subtotal"], 3, ',', '.');
        $total = number_format($total, 0, ',', '.');
     
       
        return response()->json([
            'message' => 'Chart Berhasil Di Tampilkan',
            'data' => $charts,
            'total_subtotal' => $total,
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
