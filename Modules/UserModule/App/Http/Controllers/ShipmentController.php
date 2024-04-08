<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShipmentController extends Controller
{
    public function GetProvince(){
        // raja ongkir url
        $url = "https://api.rajaongkir.com/starter/province";
        // api key header
        $headers = array(
            env('RAJA_ONGKIR_API')
        );
        // pake curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return response()->json([
            'message' => 'List Provinsi',
            'data' => json_decode($output),
            'status' => 200
        ]);
    }
}
