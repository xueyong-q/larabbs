<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use Illuminate\Auth\AuthenticationException;

class UsersController extends Controller
{
    /**
     * 注册用户
     *
     * @param \App\Http\Requests\Api\UserRequest $request
     *
     * @return void
     */
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return (new UserResource($user))->showSensitiveFields();
    }

    /**
     * 获取指定用户信息
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function show(User $user, Request $request)
    {
        return new UserResource($user);
    }

    /**
     * 获取当前登录用户信息
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function me(Request $request)
    {
        return (new UserResource($request->user()))->showSensitiveFields();
    }

    /**
     * 更新用户信息
     *
     * @param \App\Http\Requests\Api\UserRequest $request
     *
     * @return void
     */
    public function update(UserRequest $request)
    {
        $user = $request->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return (new UserResource($user))->showSensitiveFields();
    }
}
