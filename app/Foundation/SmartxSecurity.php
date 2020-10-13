<?php
/**
 * Created by PhpStorm.
 * User: liujing
 * Date: 2018/9/11
 * Time: 上午10:12
 */

namespace App\Foundation;

use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\BcryptHasher;

class SmartxSecurity extends BcryptHasher implements Hasher
{
    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }
        if($value === $hashedValue){
            return true;
        }else{
            return password_verify($value, $hashedValue);
        }

    }
}