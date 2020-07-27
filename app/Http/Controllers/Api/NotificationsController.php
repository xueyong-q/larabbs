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

    /**
     * 统计未读消息数量
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function stats(Request $request)
    {
        return response()->json([
            'unread_count' => $request->user()->notification_count,
        ]);
    }

    /**
     * 标记通知信息为已读
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function read(Request $request)
    {
        $request->user()->markAsRead();

        return response(null, 204);
    }
}
