<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * 显示用户个人资料页面
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 编辑个人资料页面
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 更新用户资料
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
