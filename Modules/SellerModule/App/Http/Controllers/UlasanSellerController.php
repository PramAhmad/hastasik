<?php

namespace Modules\SellerModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\SellerModule\App\Models\Seller;

class UlasanSellerController extends Controller
{
    public function getReviewsBySeller(Request $request)
{
    $sellerId = Seller::where('user_id', auth()->user()->id)->first()->id;
    $products = DB::connection('mongodb')->table('products')
        ->where('seller.id', $sellerId)
        ->get();
    $reviews = [];
    foreach ($products as $product) {   
        if (isset($product->review)) {
            $reviews = array_merge($reviews, $product->review);
        }
    }

    return response()->json([
        'message' => 'success',
        'data' => $reviews,
    ]);
}
}
