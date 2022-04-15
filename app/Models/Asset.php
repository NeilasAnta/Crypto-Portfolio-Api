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

    public $timestamps = true;

    public static $validationRules = [
        'label' => 'required',
        'currency_id' => 'required|exists:currencies,id',
        'amount' => 'required|gt:0',
        'value' => 'gt:0',
        'user_id' => 'exists:users,id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
