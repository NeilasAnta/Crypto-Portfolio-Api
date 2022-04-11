<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        Asset::create(array_merge(
            $validator->validated(),
            ['value' => 1],
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
            'label' => 'required',
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $asset->update(array_merge(
            $validator->validated(),
            ['value' => 1],
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
