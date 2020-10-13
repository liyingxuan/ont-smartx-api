<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 2018/4/11
 * Time: 10:36
 */
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\TestWallet;
use App\User;
use Illuminate\Http\Request;
use App\Api\Common\RetJson;

class UserController extends Controller
{
    /**
     * Get logged user info.
     *
     * 使用的Laravel passport验证。
     * 前端使用的vue axios ：config.headers['Authorization'] = 'Bearer' + ' ' + jwtToken.getToken()
     * ！！注意Bearer后需要加一个空格，否则无法通过验证。
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser(Request $request)
    {
        $user = $request->user();
        if ($request->get('network') === 'test') {
            $testWallet = TestWallet::find(1);
            $user->keys = $testWallet->keys;
            $user->keys_pwd = $testWallet->keys_pwd;
        }

        return RetJson::format($user);
    }

    /**
     * Add Github id for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddGithubId(Request $request)
    {
        if(User::where('github_id', $request['github_id'])->first() !== null ){
            $data = 'The account already exists';
            return RetJson::formatErrors($data);
        }
        else{
            $user = User::where('id', $request->user()->id)->first();
            if ($user) {
                if(isset($request['github_id']) && isset($request['github_password'])){
                    $user->github_id = $request['github_id'];
                    $user->github_password = bcrypt($request['github_password']);
                    $user->github_name = $request['github_name'];
                    $user->save();
                    return RetJson::format($user);
                }else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Add Github id false,because of the lack of id or password'
                    ], 421);
                }
            }else {
                return response()->json(['message' => 'Nothing'], 404);
            }
        }

    }

    /**
     * Delete Github id for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteGithubId(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            $user->github_id = Null;
            $user->github_password = Null;
            $user->github_name = Null;
            $user->save();
            return RetJson::format($user);
        }else {
            return response()->json(['message' => 'Nothing'], 404);
        }
    }

    /**
     * Add OntID for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddOntId(Request $request)
    {
        if(User::where('name', $request['name'])->first() !== null ){
            $data = 'The account already exists';
            return RetJson::formatErrors($data);
        }
        else{
            $user = User::where('id', $request->user()->id)->first();
            if ($user) {
                if(isset($request['name']) && isset($request['password'])){
                    $user->name = $request['name'];
                    $user->password = bcrypt($request['password']);
                    $user->save();
                    return RetJson::format($user);
                }else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Add Github id false,because of the lack of id or password'
                    ], 421);
                }
            }else {
                return response()->json(['message' => 'Nothing'], 404);
            }
        }

    }


    /**
     * Delete Ont id for user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteOntId(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            $user->name = Null;
            $user->password = Null;
            $user->save();
            return RetJson::format($user);
        }else {
            return response()->json(['message' => 'Nothing'], 404);
        }
    }
}
