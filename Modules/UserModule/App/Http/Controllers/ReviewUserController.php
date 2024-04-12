<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\UserModule\App\Models\Customer;

class ReviewUserController extends Controller
{
   public function PostReview(Request $request){
        $validate = [
            'rating' => 'required',
            'review' => 'required',
            'product_id' => 'required',
           
        ];
        try {
            $validate = $request->validate($validate);
        } catch (\Throwable $th) {
            return response()->json([
                                "message" => "error", 
                                "data" => "data gk valid", 
                                "status" => 400]);
        }

        $productId = $validate['product_id'];

        $review = DB::connection("mongodb")->collection("products")->where('_id', $productId)->first();
        $customer = Customer::select("nama_lengkap","photo")->where('user_id', auth()->user()->id)->first();
        if (!isset($review['review'])) {
            $review['review'] = [];
        }    
        $review['review'][] = [
            'customer' =>$customer ,
            'rating' => $validate['rating'],
            'review' => $validate['review']
        ];
 
        DB::connection("mongodb")->collection("products")->where('_id', $productId)->update($review);
        
        return response()->json([
            'message' => 'Review Berhasil',
            'data' => $review,
            'status' => 200
        ]);
        
   }

    public function GetReview(Request $request){
          $validate = [
                'product_id' => 'required',
          ];
          try {
                $validate = $request->validate($validate);
          } catch (\Throwable $th) {
                return response()->json(["pmessage" => "error", "data" => "data not valid", "status" => 400]);
          }
    
          $review = DB::connection("mongodb")->collection("products")->where('_id', $validate['product_id'])->get();
          if($review){
                return response()->json([
                 'message' => 'Review Produk',
                 'data' => $review['review'],
                 'status' => 200
                ]);
          } else {
                return response()->json([
                 'message' => 'Data Gak Ada',
                 'data' => '',
                 'status' => 404
                ]);
          }
    }
}
