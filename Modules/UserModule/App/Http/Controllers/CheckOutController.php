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
        $chart = DB::connection("mongodb")->collection("chart")->where('customer_id', $customerId)->first();
    
        if (!$chart || empty($chart['product'])) {
            return response()->json([
                'message' => 'Tidak ada produk dalam chart',
                'status' => 400
            ]);
        }

        $selectedProducts = $request->input('selected_products');
        if (empty($selectedProducts)) {
            $selectedProducts = $chart['product'];
        }
    
        try {
            $transactions = [];
            foreach ($selectedProducts as $product) {
                $transactions[] = [
                    'customer_id' => $customerId,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['qty'],
                    'created_at' => now(),
                ];
            }

            DB::connection("mongodb")->collection("transactions")->insert($transactions);
    
         
            return response()->json(['message' => 'Checkout berhasil'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Checkout gagal', 'error' => $e->getMessage()], 500);
        }
    }
}    
