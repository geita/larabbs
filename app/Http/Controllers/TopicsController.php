<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    /**
     * __construct
     * @Author   manhua
     * @DateTime 2018-12-14tree simditor-2.3.6
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
	public function index(Request $request, Topic $topic, user $user, Link $link)
	{
		$topics = $topic->withOrder($request->order)->paginate(30);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    /**
     * 话题显示
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
    public function show(Request $request, Topic $topic)
    {   
        // URL 矫正
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
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
		
		return redirect()->to($topic->link())->with('message', '成功创建话题！');
	}

    /**
     * 编辑话题
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * 修改话题
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    TopicRequest $request [description]
     * @param    Topic        $topic   [description]
     * @return   [type]                [description]
     */
	public function update(TopicRequest $request, Topic $topic)
	{
        $this->authorize('update', $topic);
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('message', '更新成功！');
	}

    /**
     * 删除话题
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    Topic      $topic [description]
     * @return   [type]            [description]
     */
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '成功删除！');
	}

    /**
     * 上传图片
     * @Author   manhua
     * @DateTime 2018-12-14
     * @param    [array]
     * @param    [object]
     * @param    Request            $request  [description]
     * @param    ImageUploadHandler $uploader [description]
     * @return   [type]                       [description]
     */
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        //初始化返回数据，默认是失败的        
        $data = [
            "success"   => false,
            "msg"       => "error message",
            "file_path" => "",
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}