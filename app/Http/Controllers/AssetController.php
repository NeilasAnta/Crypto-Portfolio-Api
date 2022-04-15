<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MarketController;
use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\User;
use Facade\FlareClient\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{

    public function index()
    {
        return response()->json(Asset::with('user')->get());
    }

    public function getByUserID($id)
    {
        if(User::find($id) != null)
        {
            return response()->json(Asset::where('user_id', $id)->get());
        }else{
            return response()->json([
                'status'  => 'error',
                'message' => 'User not exsits'
            ], 400);
        }
    }

    public function show($id)
    {
        return response()->json(Asset::with('user')->where('id', $id)->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),Asset::$validationRules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $value = ($request->value_before == null) ? MarketController::getCryptoPrice($request->currency_id) : $request->value_before;
        $userID = ($request->user_id == null) ? "1" : $request->user_id;

        Asset::create(array_merge(
            $validator->validated(),
            [
                'value_before' => $value,
                'user_id' => $userID
            ]
        ));

        return response()->json([
            'status' => 'ok',
            'message' => 'Asset successfully stored'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        if($asset == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'Asset not exsits'
            ], 400);
        }

        $validator = Validator::make($request->all(),Asset::$validationRules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 400);
        }


        $value = ($request->value_before == null) ? MarketController::getCryptoPrice($request->currency_id) : $request->value_before;

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
            ], 400);
        }

        $asset->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Asset successfully deleted'
        ], 200);
    }
}
