<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 2018/4/17
 * Time: 13:27
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Templates;
use Illuminate\Http\Request;
use App\Api\Common\RetJson;

class TemplateController extends Controller
{
    /**
     * 获取指定语言下的list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nameList(Request $request)
    {
        return RetJson::format(
            Templates::select(['id', 'name', 'desc'])->where('language', $request->get('language'))->get()
        );
    }

    /**
     * 获取指定模板的code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function code(Request $request)
    {
        return RetJson::format(Templates::find($request->get('id')));
    }
}
