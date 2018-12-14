<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * 新增用户与话题一对多关系
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * 验证是否为作者
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $model [description]
     * @return   boolean           [description]
     */
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
