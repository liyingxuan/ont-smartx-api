<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $fillable = [
        'user_id',

        'name',
        'language',
        'type',
        'compiler_version',
        'code',

        'wat',
        'abi',
        'nvm_byte_code',

        'info_name',
        'info_version',
        'info_author',
        'info_email',
        'info_desc',

        'contract_hash'
    ];
}
