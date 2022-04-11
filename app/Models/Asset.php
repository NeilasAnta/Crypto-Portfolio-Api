<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function validation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'currency' => ['required', Rule::exists('currency', 'name')],
            'amount' => 'required'
        ]);
        return $validator;
    }

    public function currency()
    {
        return $this->hasMany(Currency::class, 'currency_id');
    }
}
