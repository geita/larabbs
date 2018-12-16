<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    /**
     * 回复与话题 一对一
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }   

    /**
     * 用户与回复 一对多
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = clean($value, 'user_reply_content');
    }
}
