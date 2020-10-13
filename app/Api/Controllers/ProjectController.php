<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 2018/4/12
 * Time: 13:27
 */

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Projects;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Api\Common\RetJson;

class ProjectController extends Controller
{
    /**
     * Get all projects.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = [];
        $user = $request->user();
        if ($user->id) {
            $data = Projects::where('user_id', $user->id)->get();
        }

        return RetJson::format($data);
    }

    /**
     * Get project list info.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $data = [];
        $user = $request->user();
        if ($user->id) {
            $data = Projects::select(['id', 'name', 'language', 'type', 'created_at', 'updated_at'])
                ->where('user_id', $user->id)
                ->orderBy('updated_at','desc')
                ->get();
        }

        return RetJson::format($data);
    }

    /**
     * Verification create data.
     *
     * @param array $data
     * @return mixed
     */
    protected function validatorCreate(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'language' => 'required|string'
        ]);
    }

    /**
     * Create new project.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // 验证
        $validator = $this->validatorCreate($request->all());
        if ($validator->fails()) {
            return RetJson::formatErrors($validator->errors()->getMessages());
        }

        $user = $request->user();
        $data = [
            'user_id' => $user->id,

            'name' => $request->get('name'),
            'language' => $request->get('language'),
            'compiler_version' => $request->get('compiler_version'),
            'code' => $request->get('code'),
            'type' => $request->get('type'),

            'wat' => $request->get('wat'),
            'abi' => $request->get('abi'),
            'nvm_byte_code' => $request->get('nvm_byte_code'),

            'info_name' => $request->get('info_name'),
            'info_version' => $request->get('info_version'),
            'info_author' => $request->get('info_author'),
            'info_email' => $request->get('info_email'),
            'info_desc' => $request->get('info_desc'),

            'contract_hash' => $request->get('contract_hash')
        ];

        return RetJson::format(Projects::create($data));
    }

    /**
     * Get a project.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $data = [];
        $user = $request->user();
        if ($user->id) {
            $data = Projects::where('user_id', $user->id)->where('id', $request['id'])->first();
        }

        return RetJson::format($data);
    }

    /**
     * Update project info.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $project = Projects::where('user_id', $user->id)->where('id', $request['id'])->first();

        if ($project) {
            if (isset($request['compiler_version'])) $project->compiler_version = $request['compiler_version'];
            if (isset($request['code'])) $project->code = $request['code'];

            if (isset($request['wat'])) $project->wat = $request['wat'];
            if (isset($request['abi'])) $project->abi = $request['abi'];
            if (isset($request['nvm_byte_code'])) $project->nvm_byte_code = $request['nvm_byte_code'];

            if (isset($request['info_name'])) $project->info_name = $request['info_name'];
            if (isset($request['info_version'])) $project->info_version = $request['info_version'];
            if (isset($request['info_author'])) $project->info_author = $request['info_author'];
            if (isset($request['info_email'])) $project->info_email = $request['info_email'];
            if (isset($request['info_desc'])) $project->info_desc = $request['info_desc'];

            if (isset($request['contract_hash'])) $project->contract_hash = $request['contract_hash'];

            $project->save();
            return RetJson::format($project);
        } else {
            return response()->json(['message' => 'Nothing'], 404);
        }
    }

    /**
     * Del a project.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $project = Projects::where('user_id', $user->id)->where('id', $request['id'])->first();

        if ($project) {
            return RetJson::format($project->delete());
        } else {
            return response()->json(['message' => 'Nothing'], 404);
        }
    }
}
