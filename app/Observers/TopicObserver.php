<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    /**
     * 创建成功前
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
    public function saving(Topic $topic)
    {
        //生成话题摘要    
        $topic->excerpt = make_excerpt($topic->body);

    }

    /**
     * 创建成功后
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
    public function saved(Topic $topic)
    {
        //如果slug 字段无内容， 即使用翻译器 title 进行翻译
         if (!$topic->slug) {
            //直接请求 存储
            //$topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
            
            //推送任务到队列中
            dispatch(new TranslateSlug($topic));
            
         }
    }

    /**
     * 删除成功之后删除对应的回复
     * @Author   manhua
     * @DateTime 2018-12-23
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}

