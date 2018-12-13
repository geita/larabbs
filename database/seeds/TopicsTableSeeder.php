<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {

        //所有用户 ID 数组 [1, 2, 3, 4]
        $userIds = User::all()->pluck('id')->toArray();
        
        //所有分类 ID 数组 [1, 2, 3, 4]
        $categoryIds = Category::all()->pluck('id')->toArray();

        // 获取 Faker实例
        $faker = app(Faker\Generator::class);

        $topics = factory(Topic::class)
                        ->times(100)
                        ->make()
                        ->each(function ($topic, $index)
                            use ($userIds, $categoryIds, $faker)
        {
            // 从用户ID数组中随机取出一个并赋值
            $topic->user_id = $faker->randomElement($userIds);

            //话题分类，同上
            $topic->category_id = $faker->randomElement($categoryIds);
        });

        //将数据集合转换为数组，并插入到数据库中
        Topic::insert($topics->toArray());
    }

}

