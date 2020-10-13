<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 16/4/18
 * Time: 下午3:09
 */

namespace App\Api\Common;

use App\Api\Controllers\BaseController;

/**
 * Class HospitalsController
 * @package App\Api\Controllers
 */
class ApiDoc extends BaseController
{
    /**
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $http = env('MY_API_HTTP_HEAD', 'http://localhost');
        $prefix = 'api/';
        $version = 'v1';
        $url = $http . $prefix . $version;

        $api = [
            '统一说明' => [
                '域名' => $http,
                '数据格式' => 'JSON',
                '数据结构(response字段)' => [
                    'code' => '状态码（1000 | -1）',
                    'info' => '状态信息（success | fail）或报错信息；在HTTP状态码非200时,一般是报错信息',
                    'data' => '数据块',
                    'debug' => '只有内测时某些功能有该字段,用于传递一些非公开数据或调试信息'
                ],
                'url字段' => 'HTTP请求地址; {}表示在链接后直接跟该数据的ID值即可,例:http://api-url/v1/data/77?token=xx,能获取id为77的data信息',
                'method字段' => 'GET / POST',
                'form-data字段' => '表单数据',
                'response字段' => '数据结构',
                'HTTP状态码速记' => [
                    '释义' => 'HTTP状态码有五个不同的类别:',
                    '1xx' => '临时/信息响应',
                    '2xx' => '成功; 200表示成功获取正确的数据; 204表示执行/通讯成功,但是无返回数据',
                    '3xx' => '重定向',
                    '4xx' => '客户端/请求错误; 需检查url拼接和参数; 在我们这会出现可以提示的[message]或需要重新登录获取token的[error]',
                    '5xx' => '服务器错误; 可以提示服务器崩溃/很忙啦~',
                ],
            ],

            '无需token的接口' => [
                '注册' => $this->register($url),
                '登录' => $this->login($url),
                'github登陆' => $this->githubLogin($url),
            ],

            '需要token的接口' => [
                '刷新token' => $this->refresh($url),
                '登出' => $this->logout($url),
                '绑定GithubId'=> $this->AddGithubId($url),

                '登录用户信息' => $this->userInfo($url),


                '模板' => [
                    '名称列表' => $this->templateList($url),
                    'Code' => $this->templateCode($url),
                ],

                '项目' => [
                    '获取全部项目信息' => $this->allProject($url),
                    '获取列表' => $this->listProject($url),
                    '获取单个' => $this->oneProject($url),
                    '新建' => $this->createProject($url),
                    '修改' => $this->updateProject($url),
                    '删除' => $this->delProject($url)
                ],

                '公共库文件' => [
                    '获取全部项目信息' => $this->allPublicLib($url),
                    '获取列表' => $this->listPublicLib($url)
                ]
            ]
        ];

        return response()->json(compact('api'));
    }

    /**
     * 注册
     *
     * @param $url
     * @return array
     */
    public function register($url)
    {
        return [
            'url' => $url . '/register',
            'method' => 'POST',
            'params' => [
                'name' => '用户名',
                'password' => '用户密码'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'id' => '用户ID',
                    'name' => '用户名',
                    'updated_at' => '最后更新时间',
                    'created_at' => '创建时间'
                ]
            ]
        ];
    }

    /**
     * 登录
     *
     * @param $url
     * @return array
     */
    public function login($url)
    {
        return [
            '说明' => '如果没有注册，则会自动注册，并自动登录，返回正常登录之后的数据',
            'url' => $url . '/login',
            'method' => 'POST',
            'params' => [
                'name' => '用户名',
                'password' => '用户密码'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'token' => 'token',
                    'auth_id' => 'auth id',
                    'expires_in' => '有效时间（秒）'
                ]
            ]
        ];
    }

    /**
     * Github登录
     *
     * @param $url
     * @return array
     */
    public function githubLogin($url)
    {
        return [
            '说明' => '如果没有绑定该github账号，则返回登陆失败，用户使用文件重新登录并绑定',
            'url' => $url . '/github/login',
            'method' => 'POST',
            'params' => [
                'github_id' => 'github Id',
                'github_password' => 'github密码',
                'github_name' => 'github用户名'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'token' => 'token',
                    'auth_id' => 'auth id',
                    'expires_in' => '有效时间（秒）'
                ]
            ]
        ];
    }

    /**
     * 刷新token
     *
     * @param $url
     * @return array
     */
    public function refresh($url)
    {
        return [
            'url' => $url . '/token/refresh',
            'method' => 'POST',
            'params' => [],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'token' => 'token',
                    'auth_id' => 'auth id',
                    'expires_in' => '有效时间（秒）'
                ]
            ]
        ];
    }

    /**
     * 登出
     *
     * @param $url
     * @return array
     */
    public function logout($url)
    {
        return [
            'url' => $url . '/logout',
            'method' => 'POST',
            'params' => [],
            'ret.http.code' => '204',
            'response' => []
        ];
    }

    /**
     * 绑定GitHub账号
     *
     * @param $url
     * @return array
     */
    public function AddGithubId($url)
    {
        return [
            'url' => $url . '/user/github/add',
            'method' => 'POST',
            'params' => [
                'github_id' => 'github Id',
                'github_password' => 'github密码',
                'github_name' => 'github用户名'
            ],
            'response' => [
                'code' => '',
                'info' => '',
            ]
        ];
    }

    /**
     * 绑定OntId账号
     *
     * @param $url
     * @return array
     */
    public function AddOntId($url)
    {
        return [
            'url' => $url . '/user/ontid/add',
            'method' => 'POST',
            'params' => [
                'name' => 'OntId',
                'password' => 'OntId密码',
            ],
            'response' => [
                'code' => '',
                'info' => '',
            ]
        ];
    }

    /**
     * 解绑GitHub账号
     *
     * @param $url
     * @return array
     */
    public function DeleteGithubId($url)
    {
        return [
            'url' => $url . '/user/github/add',
            'method' => 'POST',
            'response' => [
                'code' => '',
                'info' => '',
            ]
        ];
    }

    /**
     * 解绑GitHub账号
     *
     * @param $url
     * @return array
     */
    public function DeleteOntId($url)
    {
        return [
            'url' => $url . '/user/ontid/delete',
            'method' => 'POST',
            'response' => [
                'code' => '',
                'info' => '',
            ]
        ];
    }

    /**
     * 用户信息
     *
     * @param $url
     * @return array
     */
    public function userInfo($url)
    {
        return [
            'url' => $url . '/user/info',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'network' => 'test | main'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'id' => '',
                    'email' => '',
                    'name' => '',
                    'github_id'=>'',
                    'github_name'=>'',
                    'created_at' => '',
                    'updated_at' => '',
                    'keys' => 'test网络的公开加密私钥',
                    'keys_pwd' => 'test网络的公开加密私钥的密码'
                ]
            ]
        ];
    }

    /**
     * 获取指定语言下的模板名称列表
     *
     * @param $url
     * @return array
     */
    public function templateList($url)
    {
        return [
            '说明' => '根据用户选择的开发语言，获取模板列表',
            'url' => $url . '/template/list',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'language' => '用户选择的语言，参数值为：CSharp | Python | JS'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '模板ID',
                        'name' => '模板名称',
                        'desc' => '模板描述'
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取指定模板代码
     *
     * @param $url
     * @return array
     */
    public function templateCode($url)
    {
        return [
            '说明' => '根据用户选择模板，获得示例代码',
            'url' => $url . '/template/code',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'id' => '用户选择的模板id'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '',
                        'name' => '',
                        'language' => '',
                        'code' => '',
                        'created_at' => '',
                        'updated_at' => ''
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取全部项目信息
     *
     * @param $url
     * @return array
     */
    public function allProject($url)
    {
        return [
            'url' => $url . '/project',
            'method' => 'GET',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '',
                        'user_id' => '',
                        'name' => '',
                        'language' => '',
                        'type' => '项目类型：normal | wasm',
                        'code' => '',
                        'wat' => 'wasm项目才有的专项',
                        'abi' => '',
                        'nvm_byte_code' => '在wasm中存储wasm',
                        'info_name' => '',
                        'info_version' => '',
                        'info_author' => '',
                        'info_email' => '',
                        'info_desc' => '',
                        'contract_hash' => '',
                        'created_at' => '',
                        'updated_at' => ''
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取项目列表
     *
     * @param $url
     * @return array
     */
    public function listProject($url)
    {
        return [
            'url' => $url . '/project/list',
            'method' => 'GET',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '',
                        'name' => '',
                        'language' => '',
                        'type' => '项目类型：normal | wasm',
                        'created_at' => '',
                        'updated_at' => ''
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取单个项目信息
     *
     * @param $url
     * @return array
     */
    public function oneProject($url)
    {
        return [
            'url' => $url . '/project/{项目ID，不要加大括号}',
            'method' => 'GET',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'id' => '',
                    'user_id' => '',
                    'name' => '',
                    'language' => '',
                    'type' => '项目类型：normal | wasm',
                    'compiler_version' => '',
                    'code' => '',
                    'wat' => 'wasm项目才有的专项',
                    'abi' => '',
                    'nvm_byte_code' => '在wasm中存储wasm',
                    'info_name' => '',
                    'info_version' => '',
                    'info_author' => '',
                    'info_email' => '',
                    'info_desc' => '',
                    'contract_hash' => '',
                    'created_at' => '',
                    'updated_at' => ''
                ]
            ]
        ];
    }

    /**
     * 新建
     *
     * @param $url
     * @return array
     */
    public function createProject($url)
    {
        return [
            'url' => $url . '/project/create',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'name' => '项目名称；必选参数',
                'language' => '项目语言；必选参数',
                'type' => '项目类型：normal | wasm；必选参数',
                'compiler_version' => '编译器版本；可选参数',
                'code' => '项目代码；必选参数',
                'wat' => 'wasm项目才有的专项；可选参数',
                'abi' => '可选参数',
                'nvm_byte_code' => '在wasm中存储wasm；可选参数',
                'info_name' => '可选参数',
                'info_version' => '可选参数',
                'info_author' => '可选参数',
                'info_email' => '可选参数',
                'info_desc' => '可选参数',
                'contract_hash' => '可选参数'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'id' => '',
                    'user_id' => '',
                    'name' => '',
                    'language' => '',
                    'type' => '项目类型：normal | wasm',
                    'code' => '',
                    'wat' => 'wasm项目才有的专项',
                    'abi' => '',
                    'nvm_byte_code' => '在wasm中存储wasm',
                    'info_name' => '',
                    'info_version' => '',
                    'info_author' => '',
                    'info_email' => '',
                    'info_desc' => '',
                    'contract_hash' => '',
                    'created_at' => '',
                    'updated_at' => ''
                ]
            ]
        ];
    }

    /**
     * 修改项目
     *
     * @param $url
     * @return array
     */
    public function updateProject($url)
    {
        return [
            'url' => $url . '/project/update',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                '说明' => '以下参数，传哪些就修改哪些；注意：如果只传了key，而value是空，则会覆盖掉数据库中的数据',
                'id' => '需要修改的项目id',
                'code' => '',
                'wat' => 'wasm项目才有的专项',
                'abi' => '',
                'nvm_byte_code' => '在wasm中存储wasm',
                'info_name' => '',
                'info_version' => '',
                'info_author' => '',
                'info_email' => '',
                'info_desc' => '',
                'contract_hash' => ''
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    'id' => '',
                    'user_id' => '',
                    'name' => '',
                    'language' => '',
                    'type' => '项目类型：normal | wasm',
                    'code' => '',
                    'compiler_version' => '编译器版本；可选参数',
                    'wat' => 'wasm项目才有的专项',
                    'abi' => '',
                    'nvm_byte_code' => '在wasm中存储wasm',
                    'info_name' => '',
                    'info_version' => '',
                    'info_author' => '',
                    'info_email' => '',
                    'info_desc' => '',
                    'contract_hash' => '',
                    'created_at' => '',
                    'updated_at' => ''
                ]
            ]
        ];
    }

    /**
     * 删除项目
     *
     * @param $url
     * @return array
     */
    public function delProject($url)
    {
        return [
            'url' => $url . '/project/del',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'id' => '需要删除的项目id'
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => 'true | false'
            ]
        ];
    }

    /**
     * 获取全部公开库信息
     *
     * @param $url
     * @return array
     */
    public function allPublicLib($url)
    {
        return [
            'url' => $url . '/public-libs',
            'method' => 'GET',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '',
                        'name' => '',
                        'language' => '开发语言：c | c++ | rust',
                        'type' => '项目类型：normal | wasm',
                        'code' => '',
                        'created_at' => '',
                        'updated_at' => ''
                    ]
                ]
            ]
        ];
    }

    /**
     * 获取指定语言下的公开库列表
     *
     * @param $url
     * @return array
     */
    public function listPublicLib($url)
    {
        return [
            'url' => $url . '/public-libs/list',
            'method' => 'POST',
            'http.headers' => [
                '说明' => 'Bearer后面有一个空格，然后拼接上该用户的token',
                'key' => 'Authorization',
                'value' => 'Bearer [TOKEN]'
            ],
            'params' => [
                'language' => '指定开发语言：c | c++ | rust',
            ],
            'response' => [
                'code' => '',
                'info' => '',
                'data' => [
                    [
                        'id' => '',
                        'name' => '',
                        'language' => '开发语言：c | c++ | rust',
                        'type' => '项目类型：normal | wasm',
                        'code' => '',
                        'created_at' => '',
                        'updated_at' => ''
                    ]
                ]
            ]
        ];
    }

}
