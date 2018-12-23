<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable {
        notify as protected laravelNotify;
    }
    use Traits\ActiveUserHelper;
    
    public function notify($instance)
    {
        //如果要通知的人是当前用户，那就不必通知了
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

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

    /**
     * 用户与回复 一对多
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * 修改器
     * @Author   manhua
     * @DateTime 2018-12-23
     * @param    [array]
     * @param    [object]
     * @param    [type]     $value [description]
     */
    public function setPasswordAttribute($value)
    {
        //如果值的长度等于60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            //不等于 60，做密码加密处理
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($value)
    {
        //如果不是 `http`字符串开头，那就是从后台上传的，需要补全url
        if (!starts_with($value, 'http')) {
            //拼接完整URL
            $value = config('app.url') . "/uploads/images/avatar/$value";
        }
        $this->attributes['avatar'] = $value;
    }

}
