<?php
/**
 * Created by PhpStorm.
 * User: liujing
 * Date: 2018/9/11
 * Time: 上午10:12
 */

namespace App\Foundation\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\User;

class SmartxEloquentUserProvider extends EloquentUserProvider implements UserProvider
{

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}