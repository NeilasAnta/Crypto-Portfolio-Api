<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarketController extends Controller
{
    public function getCryptoPrice($id)
    {
        $slug = Currency::find($id)->slug;
        $response = Http::get('https://api.coincap.io/v2/assets/'.$slug);
        $response = json_decode($response, true);
        return $response['data']['priceUsd'];
    }

    public function calculateTotalValue($userID)
    {
        if(User::find($userID) == null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not exsits'
            ], 400);
        }

        if(Asset::where('user_id', $userID)->first() == null){
            return response()->json([
                'status'  => 'error',
                'message' => 'User not have assets'
            ], 400);
        }

        $assets = Asset::with('currency')->where('user_id', $userID)->get();
        $totalValueNow = 0;
        $totalValueBefore = 0;
        foreach ($assets as $asset)
        {
            $totalValueNow += $asset->amount * MarketController::getCryptoPrice($asset->currency_id);
            $totalValueBefore += $asset->amount * $asset->value_before;
        }

        $difference = $totalValueNow - $totalValueBefore;
        $differenceProc = ($difference / $totalValueBefore) * 100;

        return array_merge([
            'totalValueNowUSD' => $totalValueNow,
            'totalValueBeforeUSD' => $totalValueBefore,
            'differenceUSD' => $difference,
            'differenceProc' => $differenceProc
        ]);
    }

//    public function calculateDifference($id, $price)
//    {
//        $priceNow = MarketController::getCryptoPrice($id);
//        $difference = $priceNow - $price;
//        $differenceProc = ($difference / $price) * 100;
//        return array_merge([
//            'differenceUsd' => $difference,
//            'differenceProc' => $differenceProc
//        ]);
//    }
}
