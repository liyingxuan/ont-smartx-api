<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 2018/5/8
 * Time: 11:27
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\PublicLibs;
use Illuminate\Http\Request;
use App\Api\Common\RetJson;

class PublicLibController extends Controller
{
    /**
     * Get all public libs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return RetJson::format(PublicLibs::all());
    }

    /**
     * Get public list info.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return RetJson::format(PublicLibs::where('language', $request->get('language'))->get());
    }
}
