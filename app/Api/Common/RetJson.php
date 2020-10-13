<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 2017/12/24
 * Time: 09:33
 */
namespace App\Api\Common;

class RetJson
{
    /**
     * 格式化正确信息
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function format($data)
    {
        return response()->json([
            'code' => 1000,
            'info' => 'success',
            'data' => $data
        ]);
    }

    /**
     * 格式化错误信息
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function formatErrors($data)
    {
        return response()->json([
            'code' => -1,
            'info' => 'fail',
            'data' => $data
        ]);
    }

    /**
     * 对通过DB查询回来得到数据进行重构
     *
     * @param $data
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function formatDbSelect($data, $request)
    {
        $page = $request->get('page');
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (is_null($page)) {
            $page = 1;
        } else {
            $url = strstr($url, '?page=', true);
        }

        $retData = [
            'current_page' => $page,
            'data' => $data,
            'next_page_url' => $url . '?page=' . ($page + 1)
        ];

        return self::format($retData);
    }
}
