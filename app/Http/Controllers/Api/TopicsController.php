<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    /**
     * 发布文章
     *
     * @param \App\Http\Requests\Api\TopicRequest $resuest
     * @param \App\Models\Topic $topic
     *
     * @return void
     */
    public function store(TopicRequest $resuest, Topic $topic)
    {
        $topic->fill($resuest->all());
        $topic->user_id = $resuest->user()->id;
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

        return new TopicResource($topic);
    }
}
