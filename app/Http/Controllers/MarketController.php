<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarketController extends Controller
{
    public function getCryptoPrice($name)
    {
        $slug = Currency::where('name', $name)->first()->slug;
        $response = Http::get('https://api.coincap.io/v2/assets/'.$slug);
        $response = json_decode($response, true);
        return $response['data']['priceUsd'];
    }

    public function calculateDifference($name, $price)
    {
        $priceNow = getCryptoPrice($name);
        $difference = $priceNow - $price;
        $differenceProc = $difference / $price * 100;
        return array_merge([
            'differenceUsd' => $difference,
            'differenceProc' => $differenceProc
        ]);
    }
}
