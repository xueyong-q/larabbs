<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;

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
		return view('topics.create_and_edit', compact('topic'));
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

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
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

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}
}