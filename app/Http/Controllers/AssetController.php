<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MarketController;
use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Models\Currency;
use Facade\FlareClient\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{

    public function index()
    {
        return response()->json(Asset::all());
    }

    public function show($id)
    {
        return response()->json(Asset::where('id', $id)->get());
    }

    public function getBTC()
    {
        $id = Currency::where('name', 'BTC')->first()->id;
        dd(MarketController::calculateDifference($id, 38000));

    }

    public function store(Request $request)
    {
        //dd($request);
        $validatedData = $request->validate();
        $validator = Validator::make($request->all(),Asset::$validationRules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $value = ($request->value == null) ? MarketController::getCryptoPrice($request->currency_id) : $request->value;

        Asset::create(array_merge(
            $validatedData,
            ['value_before' => $value]
        ));
        return response()->json([
            'status' => 'ok',
            'message' => 'Asset successfully stored'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        if($asset == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Asset not exsits'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'label' => ['required', 'unique'],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'amount' => ['required', 'gt:0'],
            'value' => 'gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $value = ($request->value == null) ? MarketController::getCryptoPrice($request->currency_id) : $request->value;

        $asset->update(array_merge(
            $validator->validated(),
            ['value_before' => $value],
        ));

        $asset->save();

        return response()->json([
            'status' => 'ok',
            'message' => 'Asset successfully updated'
        ], 200);
    }

    public function delete($id)
    {
        $asset = Asset::find($id);

        if($asset == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Asset not exsits'
            ], 422);
        }

        $asset->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Asset successfully deleted'
        ], 200);
    }
}
