<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /**
     * 回复话题
     *
     * @param \App\Http\Requests\Api\ReplyRequest $request
     * @param \App\Models\Topic $topic
     * @param \App\Models\Reply $reply
     *
     * @return void
     */
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic()->associate($topic);
        $reply->user()->associate($request->user());

        $reply->save();

        return new ReplyResource($reply);
    }
}
