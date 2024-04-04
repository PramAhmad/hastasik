<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReviewUserController extends Controller
{
   public function PostReview(Request $request){
        $validate = [
            'rating' => 'required',
            'review' => 'required',
            'product_id' => 'required'
        ];
        try {
            $validate = $request->validate($validate);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error", "data" => "data not valid", "status" => 400]);
        }

        $review = DB::connection("mongodb")->collection("products")->where('product_id', $validate['product_id'])->first();
        // create for review isinya rating dan review
        $review['review'][] = [
            'rating' => $validate['rating'],
            'review' => $validate['review']
        ];
        // update review
        DB::connection("mongodb")->collection("products")->where('product_id', $validate['product_id'])->update($review);
        return response()->json([
            'message' => 'Review Berhasil',
            'status' => 200
        ]); 
   }
}
