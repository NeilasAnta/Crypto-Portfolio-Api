<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MarketController extends Controller
{
    public function getCryptoPrice($id)
    {
        $slug = Currency::find($id)->slug;
        $response = Http::get('https://api.coincap.io/v2/assets/'.$slug);
        $response = json_decode($response, true);
        return $response['data']['priceUsd'];
    }

    private function findValueNowAndBefore($assets, &$totalValueNow, &$totalValueBefore, &$difference, &$differenceProc)
    {
        $totalValueNow = 0;
        $totalValueBefore = 0;
        $difference = 0;
        $differenceProc = 0;

        foreach ($assets as $asset)
        {
            $totalValueNow += $asset->amount * MarketController::getCryptoPrice($asset->currency_id);
            $totalValueBefore += $asset->amount * $asset->value_before;
        }

        $difference = $totalValueNow - $totalValueBefore;
        $differenceProc = ($difference / $totalValueBefore) * 100;
    }

    public function calculateTotalValue($userID)
    {
        $user = User::find($userID);

        if($user == null) {
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

        $this->findValueNowAndBefore($assets, $totalValueNow, $totalValueBefore, $difference, $differenceProc);


        return response()->json([
            'userID' => $user->id,
            'userName' => $user->name,
            'userEmail' => $user->email,
            'totalValueNowUSD' => $totalValueNow,
            'totalValueBeforeUSD' => $totalValueBefore,
            'differenceUSD' => $difference,
            'differenceProc' => $differenceProc
        ]);
    }

    public function calculateDifferenceOneCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', Rule::exists('users', 'id')],
            'currency' => ['required', Rule::exists('currencies', 'name')],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $currency = $validator->validated()['currency'];
        $userID = $validator->validated()['user_id'];
        $user = User::find($userID);
        $currencyID = Currency::where('name', $currency)->first()->id;

        if($user == null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not exsits'
            ], 400);
        }

        if(Asset::where('user_id', $userID)->where('currency_id', $currencyID)->first() == null){
            return response()->json([
                'status'  => 'error',
                'message' => 'User not have this currency'
            ], 400);
        }

        $assets = Asset::with('currency')->where('user_id', $userID)
                                                 ->where('currency_id', $currencyID)
                                                 ->get();

        $this->findValueNowAndBefore($assets, $totalValueNow, $totalValueBefore, $difference, $differenceProc);

        return response()->json([
            'userID' => $user->id,
            'userName' => $user->name,
            'userEmail' => $user->email,
            'currency' =>$currency,
            'totalValueNowUSD' => $totalValueNow,
            'totalValueBeforeUSD' => $totalValueBefore,
            'differenceUSD' => $difference,
            'differenceProc' => $differenceProc
        ]);
    }

    public function calculateSingleAsset($assetID)
    {
        $asset = Asset::find($assetID);
        if($asset == null){
            return response()->json([
                'status'  => 'error',
                'message' => 'Asset not exsits'
            ], 400);
        }

        $currency = Currency::find($asset->currency_id)->name;

        $totalValueNow = $asset->amount * MarketController::getCryptoPrice($asset->currency_id);
        $totalValueBefore = $asset->amount * $asset->value_before;

        $difference = $totalValueNow - $totalValueBefore;
        $differenceProc = ($difference / $totalValueBefore) * 100;

        return response()->json([
            'currency' =>$currency,
            'valueNowUSD' => $totalValueNow,
            'valueBeforeUSD' => $totalValueBefore,
            'differenceUSD' => $difference,
            'differenceProc' => $differenceProc
        ]);
    }
}
