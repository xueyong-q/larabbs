<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * 消息通知列表
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();

        return NotificationResource::collection($notifications);
    }
}
