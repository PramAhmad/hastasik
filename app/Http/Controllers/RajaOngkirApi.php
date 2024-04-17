<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirApi extends Controller
{
    public function getProvince()
    {
       $response =  Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')])
            ->get('https://api.rajaongkir.com/starter/province')
            ->throw();

        return response()->json($response->json());

    }

    public function getCity(Request $request)
    {
        // Validasi apakah parameter 'province' telah diberikan
        $request->validate([
            'province' => 'required',
        ]);
    
        $provinceId = $request->input('province');
    
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get('https://api.rajaongkir.com/starter/city?province=' . $provinceId)
            ->throw();
    
        return response()->json($response->json());
    }


    public function getCost(Request $request)
    {
        $response =  Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')])
            ->post('https://api.rajaongkir.com/starter/cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ])
            ->throw();

        return response()->json($response->json());
    }
}
