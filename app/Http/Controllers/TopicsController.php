<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	/**
	 * 话题列表
	 *
	 * @return void
	 */
	public function index(Request $request, Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(20);
		return view('topics.index', compact('topics'));
	}

	/**
	 * 话题详情
	 *
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

	/**
	 * 新增话题页面
	 *
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
	public function create(Topic $topic)
	{
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	/**
	 * 新建话题
	 *
	 * @param \App\Http\Requests\TopicRequest $request
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();

		return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
	}

	/**
	 * 话题编辑页
	 *
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
	public function edit(Topic $topic)
	{
		$this->authorize('update', $topic);
		$categories = Category::all();
		
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	/**
	 * 更新话题
	 *
	 * @param \App\Http\Requests\TopicRequest $request
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('success', '更新成功！');
	}

	/**
	 * 删除话题
	 *
	 * @param \App\Models\Topic $topic
	 *
	 * @return void
	 */
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功！');
	}

	/**
	 * 图片上传
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Handlers\ImageUploadHandler $uploader
	 *
	 * @return void
	 */
	public function uploadImage(Request $request, ImageUploadHandler $uploader)
	{
		// 初始化返回数据，默认是失败的
		$data = [
			'success' => false,
			'msg' => '上传失败！',
			'file_path' => '',
		];

		// 判断是否有上传文件，并赋值给 $file
		if ($file = $request->upload_file) {
			// 保存图片到本地
			$result = $uploader->save($request->upload_file, 'topic', \Auth::id(), 1024);
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