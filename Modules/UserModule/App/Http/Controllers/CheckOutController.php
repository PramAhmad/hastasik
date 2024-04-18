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

    public function checkout(Request $request) {
        $customerId = Customer::where('user_id', auth()->user()->id)->pluck("id")->first();
    
        $selectedProducts = $request->input('products');
    
        if (empty($selectedProducts)) {
            return response()->json([
                'message' => 'Tidak ada produk yang dipilih',
                'status' => 400
            ]);
        }
    
        try {
    
            $transaction = [
                'customer_id' => $customerId,
                'products' => []
            ];
    
            foreach ($selectedProducts as $product) {
                $transaction['products'][] = [
                    'product' => DB::connection("mongodb")->collection("products")->where('_id', $product['product_id'])->first(),
                    'quantity' => $product['quantity']
                ];
            }
            DB::connection("mongodb")->collection("transactions")->insert($transaction);
    
            return response()->json(['message' => 'Checkout berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Checkout gagal', 'error' => $e->getMessage()], 500);
        }
    }
    
    
}    
