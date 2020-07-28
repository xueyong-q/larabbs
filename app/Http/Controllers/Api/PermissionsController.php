<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * 用户权限列表
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();

        PermissionResource::wrap('data');

        return PermissionResource::collection($permissions);
    }
}
