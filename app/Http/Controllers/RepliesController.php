<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	/**
     * 发表回复
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    ReplyRequest $request [description]
     * @param    Reply        $reply   [description]
     * @return   [type]                [description]
     */
	public function store(ReplyRequest $request, Reply $reply)
	{
        $reply->topic_id = $request->topic_id;
        $reply->content = $request->content;
        $reply->user_id = Auth::id();
        $reply->save();

		return redirect()->to($reply->topic->link())->with('success', '创建成功！');
	}

    /**
     * 删除回复
     * @Author   manhua
     * @DateTime 2018-12-16
     * @param    [array]
     * @param    [object]
     * @param    Reply      $reply [description]
     * @return   [type]            [description]
     */
	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->to($reply->topic->link())->with('success', '成功删除回复!');
	}
}