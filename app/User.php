<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The login type，including keystore and github sign in type
     * 登陆的方式，现包括证书登陆和github登陆
     * Keystore => 0
     * github => 1
     *
     * @var int
     */
    protected static $loginType = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'password','github_id','github_password','github_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','github_password'
    ];

    /**
     * 提供用户名登录方式
     *
     * @param $username
     * @return mixed
     */
    public static function findEmail($username)
    {
        return User::where('name', $username)->first();
    }

    /**
     * Select the corresponding password field when using different login methods
     * 使用不同的登录方式时选择相对应的password字段
     * Keystore --> password
     * github --> github_password
     *
     * @return mixed
     */

    public function getAuthPassword() {
/*        if(self::$loginType == 0){
            return $this->password;
        }else
         if(self::$loginType == 1){
            return $this->github_password;
        }*/

        if($this->name){
            return $this->password;
        }else if($this->github_id){
            return $this->github_password;
        }else{
            return $this->password;
        }
    }

    public static function setLoginType($type){
        self::$loginType = $type;
    }

}
