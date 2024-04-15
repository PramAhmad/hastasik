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

    public function getCity(Request $request){
        if($request->province_id){
            $response =  Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')])
                ->get('https://api.rajaongkir.com/starter/city?province='.$request->province_id)
                ->throw();
        }else{
            $response =  Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')])
                ->get('https://api.rajaongkir.com/starter/city')
                ->throw();
        }
    //    jika

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
