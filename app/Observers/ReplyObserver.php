<?php

namespace App\Observers;

use App\Models\Reply;

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
     * 创建成功后 话题加1
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    Reply      $reply [description]
     * @return   [type]            [description]
     */
    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);
    }
}