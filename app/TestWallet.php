<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestWallet extends Model
{
    protected $fillable = [
        'keys',
        'keys_pwd'
    ];
}
