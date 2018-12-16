<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    /**
     * 话题与分类的模型
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 用户话题模型
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 指定排序
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $query [description]
     * @param    [type]     $order [description]
     * @return   [type]            [description]
     */
    public function scopeWithOrder($query, $order)
    {
        //不同的排序 使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        //预加载防止 N+1
        return $query->with('user', 'category');
    }

    /**
     * 按照更新时间排序
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $query [description]
     * @return   [type]            [description]
     */
    public function scopeRecentReplied($query)
    {
        //当话题有最新回复时，我们将编写逻辑来更新话题模型的 reply_count属性
        //此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * 按照创建时间排序
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $query [description]
     * @return   [type]            [description]
     */
    public function scopeRecent($query)
    {
        //按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 修改器 标题增加书名号
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $query [description]
     * @return   [type]            [description]
     */
    public function setTitleAttribute($value)
    {
        if (!strstr($value, '《')) {
            $this->attributes['title'] = '《' . trim($value, ' ') . '》';
        }
    }

    /**
     * 修改器 防XSS
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    [type]     $value [description]
     */
    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = clean($value, 'user_topic_body');
    }

    /**
     * show 链接
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    array      $params [description]
     * @return   [type]             [description]
     */
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    /**
     * 话题与回复 一对多
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
}
