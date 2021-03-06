<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 设置排序
     *
     * @param [type] $query
     * @param [type] $order
     *
     * @return void
     */
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }

        return $query;
    }

    /**
     * 预加载防止 N+1 问题
     *
     * @param [type] $query
     * @return void
     */
    public function scopeWithTopic($query)
    {
        return $query->with('user', 'category');
    }

    /**
     * 按更新时间倒序
     *
     * @param [type] $query
     *
     * @return void
     */
    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * 按写入时间倒序
     *
     * @param [type] $query
     *
     * @return void
     */
    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 更新回复统计
     *
     * @return void
     */
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
