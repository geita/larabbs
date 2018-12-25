<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    //缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        //获取今天的日期
        $date = Carbon::now()->toDateString();

        //Redis 哈希表的命名，如：larabbs_last_actived_at_2018_10_26
        $hash = $this->getHashFromDateString($date);

        // 字段名称，如 user_1
        $field = $this->getHashField();

        //当前时间
        $now = Carbon::now()->toDateTimeString();

        //数据写入Redis 字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        //获取昨天的日期
        $yesterday_date = Carbon::yesterday_date()->toDateString();

        $hash = $this->hash_prefix . $yesterday_date;

        $datas = Redis::hGetAll($hash);

        if (!empty($datas)) {
            foreach ($datas as $user_id => $actived_at) {
                $user_id = str_replace($this->field_prefix, '', $user_id);

                //只有当用户存在的时候才更新到数据库中
                if ($user = $this->find($user_id)) {
                    $user->last_actived_at = $actived_at;
                    $user->save();
                }
            }
        }

        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->hash_prefix . $date;

        $field = $this->getHashField();

        $datetime = Redis::hGet($hash, $field) ? : $value;

        if ($datetime) {
            return new Carbon($datetime);
        } else {
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        return $this->field_prefix . $this->id;
    }
}