<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\UserModule\App\Models\Customer;

class CheckOutController extends Controller
{

   public function __construct()
   {
       $this->middleware('auth:sanctum');
   }
   public function getRecipt(Request $request)
   {
    // getfrom caart
    $cartProduct = DB::connection("mongodb")->collection("chart")->where("customer_id", auth()->user()->id)->get();
    $total = 0;
    $data = [];
    foreach ($cartProduct as $key => $value) {
        $product = DB::connection("mongodb")->collection("products")->where("_id", $value["product_id"])->first();
        $total += $product["harga"];
        $data[] = $product;
    }
    $response = [
        "message" => "success",
        "total" => $total,
        "data" => $data,
        "status" => 200,
    ];
    return response()->json($response);
   }

//    insert to taransac
    
    
}    
