<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use Exception;


class UsersController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }
    /**
     * 用户详情
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function show(User $user)
    {
        try {

            throw new Exception("Error Processing Request", 123);
            
        } catch (Exception $e) {
            
        }
        return view('users.show', compact('user'));
    }

    /**
     * 编辑资料
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 更新用户
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     * @return [type]               [description]
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        
        $this->authorize('update', $user);
        $data = $request->all();
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatar', $user->id, 400);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
