<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //
    }

    public function updating(Reply $reply)
    {
        //
    }

    /**
     * 创建成功后 
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    Reply      $reply [description]
     * @return   [type]            [description]
     */
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        //话题加1
        $reply->topic->increment('reply_count', 1);

        // 通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));
    }

    /**
     * 删除成功累计减1
     * @Author   manhua
     * @DateTime 2018-12-23
     * @param    [array]
     * @param    [object]
     * @param    Reply      $reply [description]
     * @return   [type]            [description]
     */
    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count', 1);
    }
}