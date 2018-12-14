<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{
    
    public function show(Category $category, Request $request, Topic $topic)
    {
        //读取分类的 ID 关联话题，并按每 20 条分页
        $topics = $topic->withOrder($request->order)
                        ->where('category_id', $category->id)
                        ->paginate(20);
        //传参变量话题和分类到模版中
        return view('topics.index', compact('topics', 'category'));
    }
}