<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 16/4/18
 * Time: 下午5:45
 */

namespace App\Api\Controllers;

use App\User;
use App\Http\Proxy\TokenProxy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Api\Common\RetJson;

class AuthController extends BaseController
{
    protected $proxy;

    /**
     * Constructor.
     *
     * @param TokenProxy $proxy
     */
    public function __construct(TokenProxy $proxy)
    {
        $this->middleware('guest')->except('logout');
        $this->proxy = $proxy;
    }

    /**
     * Verification input data.
     *
     * @param array $data
     * @return mixed
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['name'], // 框架提供的登录字段是email
            'name' => $data['name'],
            'password' => bcrypt($data['password'])
        ]);
    }

    /**
     * Create a new user from github id
     *
     * @param array $data
     * @return mixed
     */
    protected function githubCreate(array $data)
    {
        return User::create([
            'email' => $data['github_id'], // 框架提供的登录字段是email
            'github_id' => $data['github_id'],
            'github_password' => bcrypt($data['github_password'])
        ]);
    }

    /**
     * User register.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // 验证
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return RetJson::formatErrors($validator->errors()->getMessages());
        }

        try {
            event(new Registered($user = $this->create($request->all())));

            return RetJson::format($user);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * User login return token.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (User::where('name', $request['name'])->first() === null) {
            event(new Registered($user = $this->create($request->all())));
        }

        return $this->proxy->login($request['name'], $request['password']);
    }

    /**
     * User login by github id return token.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function githubLogin(Request $request)
    {
        if (User::where('github_id', $request['github_id'])->first() === null) {
            event(new Registered($user = $this->githubCreate($request->all())));
        }
        return $this->proxy->githubLogin($request['github_id'], $request['github_password'] ,$request['github_name']);
    }

    /**
     * Refresh token.
     */
    public function refresh()
    {
        return $this->proxy->refresh();
    }

    /**
     * User logout.
     */
    public function logout()
    {
        return $this->proxy->logout();
    }
}
