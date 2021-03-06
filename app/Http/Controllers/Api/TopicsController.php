<?php

namespace App\Http\Controllers\Api;

use App\Http\Queries\TopicQuery;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopicsController extends Controller
{
    /**
     * 文章列表
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function index(Request $request, TopicQuery $query)
    {
        
        $topics = $query->paginate();

        return TopicResource::collection($topics);
    }

    /**
     * 某个用户的文章列表
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    /**
     * 发布文章
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    /**
     * 更新文章
     *
     * @param \App\Http\Requests\Api\TopicRequest $request
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return new TopicResource($topic);
    }

    /**
     * 删除文章
     *
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return response(null, 204);
    }

    /**
     * 文章详情
     *
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function show($topicId, TopicQuery $query)
    {
        $topic = $query->findOrFail($topicId);

        return new TopicResource($topic);
    }
}
