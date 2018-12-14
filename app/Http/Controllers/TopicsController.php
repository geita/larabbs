<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;

class TopicsController extends Controller
{
    /**
     * __construct
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 获取话题列表
     * @Author   manhua
     * @DateTime 2018-12-13
     * @param    [array]
     * @param    [object]
     * @return   [type]     [description]
     */
	public function index(Request $request, Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(30);
		return view('topics.index', compact('topics'));
	}

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * 发布话题指编辑界面
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
	public function create(Topic $topic)
	{
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * 发布话题之提交界面
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    TopicRequest $request [description]
     * @return   [type]                [description]
     */
	public function store(TopicRequest $request, Topic $topic)
	{
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
		
		return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}
}