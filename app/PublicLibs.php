<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicLibs extends Model
{
    protected $fillable = [
        'name',
        'language',
        'type',
        'code'
    ];
}
